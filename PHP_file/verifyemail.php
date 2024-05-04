<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "transaction");

if (isset($_POST["verification_code"])) {
    $inputCode = $_POST["verification_code"];

    if (isset($_SESSION['verification_code']) && $_SESSION['verification_code'] == $inputCode) {
   
        $email = $_SESSION['email_for_verification'];
        
     
        $stmt = $conn->prepare("UPDATE account SET Verified = ? WHERE Email = ?");
        $confirm = 'Confirm';
        $stmt->bind_param("ss", $confirm, $email);

        if ($stmt->execute()) {
        
            $stmt = $conn->prepare("SELECT id FROM account WHERE Email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
    
            if ($row) {
                echo "Verification successful!";
                $_SESSION['user_id'] = $row['id'];
                
             
                unset($_SESSION['verification_code']);
                unset($_SESSION['email_for_verification']);
                
                header("Location: ../ClientDashboard.php");
                exit();
            } else {
               
                echo "<script> alert('Error: User not found!');
                window.location = '../EmailVerification.php'</script>";
            }
        } else {
     
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "<script> alert('Invalid verification code!');
        window.location = '../EmailVerification.php'</script>";
    }
}

mysqli_close($conn);


?>
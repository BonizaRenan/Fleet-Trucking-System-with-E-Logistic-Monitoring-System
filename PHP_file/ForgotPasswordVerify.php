<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "transaction");

if (isset($_POST["Forgot_verification_code"])) {
    $inputCode = $_POST["Forgot_verification_code"];


    if ($_SESSION['Forgot_verification_code'] == $inputCode) {
    
        $email = $_SESSION['email_for_Forgot_verification'];

       
        $stmt = $conn->prepare("SELECT id FROM account WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            echo "Verification successful!";
            $_SESSION['user_id'] = $row['id'];

           
            // unset($_SESSION['Forgot_verification_code']);
            // unset($_SESSION['email_for_Forgot_verification']);

            header("Location: ../Newpassword.php");
            exit();
        } else {
            echo "<script> alert('Error: User not found!');
            window.location = '../forgotPasswordVerifycode.php'</script>";
        }
    } else {
        // echo "Invalid verification code!";
        echo "<script> alert('Invalid verification code!');
        window.location = '../forgotPasswordVerifycode.php'</script>";
    }
}
mysqli_close($conn);
?>
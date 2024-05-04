<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "transaction");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST["submit"])) {
    $useremail = $_POST["Email"];
    $password = $_POST["password"];
    
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM account WHERE Username=? OR Email=?");
    $stmt->bind_param("ss", $useremail, $useremail);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        echo "<script> alert('You are not registered'); 
        window.location = '../index.php'
        </script>";
        exit();
    }

    if ($password == $row["Password"]) {
        if ($row["Verified"] == "Pending") {
      
           $verificationCode = rand(100000, 999999);
           $_SESSION['verification_code'] = $verificationCode;
           $_SESSION['email_for_verification'] = $row['Email'];;
           
      
           require '../PHPMailer/PHPMailer.php';
           require '../PHPMailer/SMTP.php';
           require '../PHPMailer/Exception.php';
   
           $mail = new PHPMailer\PHPMailer\PHPMailer();
           $mail->isSMTP(); 
           $mail->Host = 'smtp.gmail.com';
           $mail->SMTPAuth = true; 
           $mail->Username = 'renanboniza03@gmail.com';
           $mail->Password = 'cbpigqwnrbwbypok'; 
           $mail->SMTPSecure = 'tls'; 
           $mail->Port = 587;
   
           $mail->setFrom('renanboniza03@gmail.com', 'Renan Boniza');
           $mail->addAddress($row['Email']);
   
           $mail->isHTML(true); 
           $mail->Subject = 'Your Verification Code';
           $mail->Body    = "Your verification code is: " . $verificationCode;
   
           if(!$mail->send()) {
               echo 'Mailer Error: ' . $mail->ErrorInfo;
           } else {
           
               header("Location: ../EmailVerification.php");
               exit();
            }

        } elseif ($row["Verified"] == "Confirm") {
          
            mysqli_query($conn, "UPDATE account SET Active_Status = 'Active' WHERE id =" . $row['id']);
            
            $user_type = $row["user_type"];

            switch ($user_type) {
                case 'client':
                    $_SESSION['user_id'] = $row['id'];

                    // Kunin ang redirect parameter mula sa GET request
                    $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';
            
                    // I-check kung ang redirect ay 'transactionrecord'
                    if ($redirect === 'transactionrecord') {
                        echo '<script>alert("Redirecting to ClientTransactionRecord.php");</script>';
                        header("Location: ../ClientTransactionRecord.php");
                        exit();
                    } else {
                        echo '<script>alert("Redirecting to ClientDashboard.php");</script>';
                        header("Location: ../ClientDashboard.php");
                        exit();
                    }
                    break;
                case 'admin':
                    $_SESSION['user_id'] = $row['id'];
                    header("Location: ../AdminDashboard.php");
                    exit();
                    break;
                case 'staff':
                    $_SESSION['user_id'] = $row['id'];
                    header("Location: ../StaffDashboard.php");
                    exit();
                    break;
                default:
                    echo "<script> alert('Error');
                    window.location = '../index.php'
                    </script>";
                    break;
            }
        } else {
           
            echo "<script> alert('Account status error. Please contact support.'); 
            window.location = '../index.php'
            </script>";
        }
    } else {
   
        echo "<script> alert('Invalid Password'); 
            window.location = '../index.php'
            </script>";
    }
}
?>

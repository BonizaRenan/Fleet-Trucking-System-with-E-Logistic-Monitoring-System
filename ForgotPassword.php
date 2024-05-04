<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "transaction");
$message = "";

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Email'])) {
    $email = $_POST['Email'];
    
    $verificationCode = rand(100000, 999999);
    $_SESSION['Forgot_verification_code'] = $verificationCode;
    $_SESSION['email_for_Forgot_verification'] = $email; 

    $stmt = $conn->prepare('SELECT * FROM account WHERE Email = ?');
    $stmt->bind_param('s', $email);  
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->fetch_assoc()) {
         
          require 'PHPMailer/PHPMailer.php';
          require 'PHPMailer/SMTP.php';
          require 'PHPMailer/Exception.php';

          $mail = new PHPMailer\PHPMailer\PHPMailer();
          $mail->isSMTP(); 
          $mail->Host = 'smtp.gmail.com'; 
          $mail->SMTPAuth = true; 
          $mail->Username = 'renanboniza03@gmail.com'; 
          $mail->Password = 'cbpigqwnrbwbypok'; 
          $mail->SMTPSecure = 'tls'; 
          $mail->Port = 587;

          $mail->setFrom('renanboniza03@gmail.com', 'Masbate Trucking');
          $mail->addAddress($email); 

          $mail->isHTML(true); 
          $mail->Subject = 'Your Verification Code';
          $mail->Body    = "Your verification code is: " . $verificationCode;

          if(!$mail->send()) {
              echo 'Mailer Error: ' . $mail->ErrorInfo;
          }

          header("Location: forgotPasswordVerifycode.php");
          exit();

        $message = "Email found!";
    } else {
     
        $message = "Email not found!";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ForgotPassword.css">
    <title>Forgot Password</title>
</head>
<body>
    <form action="#" method="post" class="email-verification-form">
        <p class="title">Forgot Password</p>
        <div class="input-verification">
            <div class="input-container">
                <input type="text" name="Email" placeholder="Email" required>
                <p class="error-message"><?php echo $message; ?></p>
            </div>
        </div>
        <div class="buttons">
    <input type="submit" value="Search">
    <button type="button" class="back-btn" onclick="redirectToIndex()">Go Back</button>
</div>
</form>

<script>
    function redirectToIndex() {
        window.location.href = "index.php";  
    }
</script>
</body>
</html>

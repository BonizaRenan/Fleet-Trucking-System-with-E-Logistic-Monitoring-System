<?php
session_start();
  $conn = mysqli_connect("localhost", "root", "", "transaction");
  
    if(isset($_POST["submit"])){
        $Firstname = $_POST["Firstname"];
        $Lastname = $_POST["Lastname"];
        $Username = $_POST["Username"];
        $Email = $_POST["Email"];
        $PhoneNumber =$_POST["PhoneNum"];
        $Password = $_POST["Password"];
        $confirmpassword = $_POST["confirmpassword"];
        $gender= $_POST["gender"];
        $usertype= $_POST["Usertype"];
        $ActiveStatus = $_POST["ActiveStatus"];
        $Verified = 'Pending';
     
        $verificationCode = rand(100000, 999999);
            
     
        $_SESSION['verification_code'] = $verificationCode;
        $_SESSION['email_for_verification'] = $Email; 

        $duplicate = mysqli_query($conn, "SELECT * FROM account WHERE username = '$Username' OR email = 'email'");
        if(mysqli_num_rows($duplicate)> 0 ){  
          header("Location:../Register.php?addMsg=Username or Email Has Already Taken");
          exit();
        }
        else{

         if ($Password == $confirmpassword) {
          $query = "INSERT INTO account(id, Firstname, Lastname, Username, Email, Phone_Num, Gender, Password, user_type, Active_Status, Verified) 
          VALUES(NULL, '$Firstname','$Lastname','$Username','$Email','$PhoneNumber','$gender','$Password', '$usertype','$ActiveStatus', '$Verified')";
            mysqli_query($conn, $query);
                
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

              $mail->setFrom('renanboniza03@gmail.com', 'Masbate Trucking');
              $mail->addAddress($Email); 

              $mail->isHTML(true); 
              $mail->Subject = 'Your Verification Code';
              $mail->Body    = "Your verification code is: " . $verificationCode;

              if(!$mail->send()) {
                  echo 'Mailer Error: ' . $mail->ErrorInfo;
              }

              header("Location: ../EmailVerification.php");
              exit();

          } else {
              echo "<script>
                    document.getElementById('confirmpassword').setCustomValidity('Passwords do not match.');
              </script>";
}
        }
    }

mysqli_close($conn);
?>
<?php
$conn = mysqli_connect("localhost", "root", "", "transaction");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
    // Function to send email notification
    function sendEmailNotification($toEmail, $subject, $body) {
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
        $mail->addAddress($toEmail);
    
        $mail->isHTML(true); 
        $mail->Subject = $subject;
        $mail->Body    = $body;
    
        if(!$mail->send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
    }

if (isset($_POST["submit_payment"])) {
    $packageID = $_POST["id"];
    $PaymentPrice = $_POST["PaymentPrice"];
    $PaymentPriceDesc = $_POST["PaymentPriceDesc"];

    $GetclientId = $conn->prepare("SELECT * FROM client_package WHERE id=?");
    $GetclientId->bind_param('i', $packageID);
    $GetclientId->execute();
    $GetclientIdResult = $GetclientId->get_result();
    $GetclientIdrow = $GetclientIdResult->fetch_assoc();
    $clientID = $GetclientIdrow['Client_ID'];
    $PackageName = $GetclientIdrow['Package_Name'];
    $QRText = $GetclientIdrow['QrCodeText'];
    $PackageidNum = $GetclientIdrow['PackageNameCode'];

    $stmtEmail = $conn->prepare("SELECT Email FROM account WHERE id=?");
    $stmtEmail->bind_param('i',$clientID);
    $stmtEmail->execute();
    $emailResult = $stmtEmail->get_result();
    $emailRow = $emailResult->fetch_assoc();

  
    // $stmtUpdate = $conn->prepare("UPDATE client_package SET Status_Review=? WHERE id=?");
    // $stmtUpdate->bind_param('si', $Decision, $packageID);
    // $successUpdate = $stmtUpdate->execute();
    // $stmtUpdate->close();

            $stmt1 = $conn->prepare("UPDATE payment_transaction SET Price_Payment=? ,Payment_Desc=? WHERE Package_ID=?");
            $stmt1->bind_param("ssi", $PaymentPrice,  $PaymentPriceDesc, $packageID);

            if ($stmt1->execute()) {
                // echo "<script> alert('Update Successfully');
                // window.location = '../AdminPendingPayment.php'</script>";
                $websiteLink = "http://localhost/masbatetrucking/index.php?redirect=transactionrecord"; 
                $emailSubject = "Package Review";
                $emailBody = "Dear Client, we have updated your payment for OTS: '".$PackageidNum."'. Please confirm if everything looks correct. Thank you! <br><br> Check out your Transaction on this site: <a href='$websiteLink'>$websiteLink</a>"
                ;
                sendEmailNotification($emailRow['Email'], $emailSubject, $emailBody);
                
                $successMessage = "Updated successfully.";
                header("Location:../AdminPendingPayment.php?success_message=" . urlencode($successMessage));
                exit;
               
                $stmt1->close();
            } else {
                echo "Failed to insert: " . $stmt1->error;
                $stmt1->close();
                exit;
            }
        }  else {
      
        echo "Error: " . mysqli_error($conn);
    }



  


?>
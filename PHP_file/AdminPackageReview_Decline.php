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

    if (isset($_POST["Decline_btn"])) {
        $packageID = $_POST["id"];
        $Decline = $_POST["DeclineInput"];
        $Decline_Desc = $_POST["Decline_Desc"];

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
    
        $query = "UPDATE client_package SET Status_Review='$Decline', SR_Desc_Decline='$Decline_Desc' WHERE id = '$packageID'";
            $Status = mysqli_query($conn, $query);

            if($Status) {
                // echo "<script> alert('Decline Successfully');
                // window.location = '../AdminPackageReview.php'</script>";
                
                $websiteLink = "http://localhost/masbatetrucking/index.php?redirect=transactionrecord"; 
                $emailSubject = "Package Review";
                $emailBody = "Dear Client,\n\nWe regret to inform you that your package with the reference OTS: '".$PackageidNum."' has been declined because '". $Decline_Desc."'. We apologize for any inconvenience. If you have any questions or need further assistance, please let us know. Thank you for your understanding!\n\nBest regards,\n Masbate Trucking <br><br> Check out your Transaction on this site: <a href='$websiteLink'>$websiteLink</a>";
                sendEmailNotification($emailRow['Email'], $emailSubject, $emailBody);

                $successMessage = "Package Declined Successfully.";
                header("Location: ../AdminPackageReview.php?success_message=" . urlencode($successMessage));
                exit;
            }
            
            
            else{}
    }

    else{

    }

?>
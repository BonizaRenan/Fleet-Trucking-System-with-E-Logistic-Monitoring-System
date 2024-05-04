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
    $mail->Body = $body;

    if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
}

if (isset($_POST["Decline_btn"])) {
    $packageID = $_POST["Package_id"]; 
    $Decline = $_POST["DeclineInput"];
    $Decline_Desc = $_POST["Decline_Desc"];
    $Reject = 'Reject';

   

   
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
    $stmtEmail->bind_param('i', $clientID);
    $stmtEmail->execute();
    $emailResult = $stmtEmail->get_result();
    $emailRow = $emailResult->fetch_assoc();

 
    $query = "UPDATE client_package SET Payment_Confirmation='$Reject', Payment_Confirmation_Desc='$Decline_Desc' WHERE id = '$packageID'";
    $Status = mysqli_query($conn, $query);

    if ($Status) {
        $websiteLink = "http://localhost/masbatetrucking/index.php?redirect=transactionrecord"; 
        $emailSubject = "Payment Transaction";
        $emailBody = "Dear Client,\n\nWe regret to inform you that your package the OTS: '" .$PackageidNum. "' has been declined because '" . $Decline_Desc . "'. Please check the Package Info for more details.\n\nBest regards,\n Masbate Trucking <br><br> Check out your Transaction on this site: <a href='$websiteLink'>$websiteLink</a>";
        sendEmailNotification($emailRow['Email'], $emailSubject, $emailBody);

        $successMessage = "Payment Declined Successfully.";
        header("Location: ../AdminPaymentTransaction.php?success_message=" . urlencode($successMessage));
        exit;
    } else {
     
        echo "Failed to update package status: " . mysqli_error($conn);
    }
}


    if (isset($_POST["AcceptImage"])) {
        $packageIDimage = $_POST["Image_id"]; 
        $AcceptText = 'Accept';
        $PendingText = 'Pending';
        $packageid = $_POST["package_id"];


        $query = "UPDATE payment_screenshots SET Status_image = '$AcceptText' WHERE id = '$packageIDimage'";
        $Status = mysqli_query($conn, $query);

        if ($Status) {

        $query1 = "UPDATE client_package SET Payment_Confirmation = '$PendingText' WHERE id = '$packageid'";
        $Status1 = mysqli_query($conn, $query1);

            if ($Status1) {

            // $emailSubject = "Payment Transaction";
            // $emailBody = "Dear Client,\n\nWe regret to inform you that your package with the reference OTS: '" .$PackageidNum. "' has been declined because '" . $Decline_Desc . "'. Please check the Package Info for more details.\n\nBest regards,\n Masbate Trucking";
            // sendEmailNotification($emailRow['Email'], $emailSubject, $emailBody);

            $successMessage = "Image Accepted Successfully";
            header("Location: ../AdminPaymentTransaction.php?id=" . urlencode($packageid) . "&action=show&success_message=" . urlencode($successMessage));
            exit;
            }
            else {
        
                echo "Failed to update package status: " . mysqli_error($conn);
            }
            
        } else {
        
            echo "Failed to update package status: " . mysqli_error($conn);
        }
    }

    if (isset($_POST["DeclineImage"])) {
        $packageIDimage = $_POST["Image_id"]; 
        $AcceptText = 'Decline';
        $Decline = $_POST["DeclineInput"];
        $Decline_Desc = $_POST["Decline_Desc"];
        $packageID = $_POST["Package_id"]; 
        
        $query = "UPDATE payment_screenshots SET Status_image = '$AcceptText' WHERE id = '$packageIDimage'";
        $Status = mysqli_query($conn, $query);

        if ($Status) {
            $query1 = "UPDATE client_package SET Payment_Confirmation='$Decline',Payment_Confirmation_Desc='$Decline_Desc' WHERE id = '$packageID'";
            $Status1 = mysqli_query($conn, $query1);
            if ($Status1) {

            $successMessage = "Image Declined Successfully";
            header("Location: ../AdminPaymentTransaction.php?id=" . urlencode($packageID) . "&action=show&success_message=" . urlencode($successMessage));
            exit;

            }
        } else {
        
            echo "Failed to update package status: " . mysqli_error($conn);
        }
    }
?>

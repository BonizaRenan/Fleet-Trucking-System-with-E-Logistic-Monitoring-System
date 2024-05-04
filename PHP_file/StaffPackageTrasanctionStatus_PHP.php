<?php 
$conn = mysqli_connect("localhost", "root", "", "transaction");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function generateQRBase64($text, $size = '400x400') {
    $data = urlencode($text);
    $imageURL = "https://chart.googleapis.com/chart?chs=$size&cht=qr&chl=$data&choe=UTF-8";
    return file_get_contents($imageURL);
}

function sendEmailNotification($toEmail, $subject, $body, $attachment = null) {
    require_once '../PHPMailer/PHPMailer.php';
    require_once '../PHPMailer/SMTP.php';
    require_once '../PHPMailer/Exception.php';

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

    if ($attachment) {
        $mail->addStringAttachment($attachment, 'qrcode.png', 'base64', 'image/png');
    }

    return $mail->send();
}
if(isset($_POST["Package_Update2"])) {
    $Packageid = $_POST["idtransaction"];
    $Arriving_Masbate = $_POST["Arriving_Masbate"];
    $Delivered = $_POST["Delivered"];
    
    $stmtDetails = $conn->prepare("SELECT * FROM client_package WHERE id = ?");
    $stmtDetails->bind_param('i', $Packageid);
    $stmtDetails->execute();
    $detailsResult = $stmtDetails->get_result();

    if ($detailsRow = $detailsResult->fetch_assoc()) {
        $receiverEmail = $detailsRow["Receiver_Email"];
        $qrCodeText = $detailsRow["QrCodeText"];
        $PackageName = $detailsRow['Package_Name'];
        $clientID = $detailsRow["Client_ID"];
        $PackageidNum = $detailsRow['PackageNameCode'];
        // $QRText = $GetclientIdrow['QrCodeText'];

    } else {
        echo "Error fetching package details.";
        exit;
    }
    $stmtDetails->close();

            $stmtEmail = $conn->prepare("SELECT Email FROM account WHERE id = ?");
            $stmtEmail->bind_param('i', $clientID);
            $stmtEmail->execute();
            $emailResult = $stmtEmail->get_result();
            
            if ($emailRow = $emailResult->fetch_assoc()) {
                $clientEmail = $emailRow["Email"];
            } else {
                echo "Error fetching client's email.";
                exit;
            }
            $stmtEmail->close();

            $stmtUpdate = $conn->prepare("UPDATE package_transaction SET Arrive_Masbate = ?, Arrive_Masbate_DateTime = NOW(), Delivered = ? WHERE Package_id = ?");
            $stmtUpdate->bind_param('ssi', $Arriving_Masbate, $Delivered, $Packageid);

            if (!$stmtUpdate->execute()) {
                echo "Failed to update: " . $stmtUpdate->error;
                $stmtUpdate->close();
                exit;
            }
            $stmtUpdate->close();
            $websiteLink = "http://localhost/masbatetrucking/index.php?redirect=transactionrecord"; 
            $emailSubject = "Package Has Been On Masbate";
            $emailBody = "Dear Client, Your package OTS: '".$PackageidNum."' is now on the masbate site. The QR code package has been sent to the receiver. Thank you! <br><br> Check out your Transaction on this site: <a href='$websiteLink'>$websiteLink</a>";
            $emailClientResponse = sendEmailNotification($clientEmail, $emailSubject, $emailBody);

            $imageData = generateQRBase64($qrCodeText);
            $emailBody = "Here is your QR code for the package OTS: '".$PackageidNum."' . Please find the attachment for the QR code. You can now get the package on the site.";
            $emailReceiverResponse = sendEmailNotification($receiverEmail, "Your Package QR Code", $emailBody, $imageData);

          
            // echo "<script> alert('Confirm Successfully'); window.location = '../StaffPackageTransactionStatus.php'
            // </script>";

            $successMessage = "Confirmed Successfully.";
                header("Location: ../StaffPackageTransactionStatus.php?success_message=" . urlencode($successMessage));
                exit;


    
} 
elseif(isset($_POST["Delay_package"])){
    $Packageid = $_POST["idtransaction"];
    $DelayConfirm = 'Confirm';

    $stmtDetails = $conn->prepare("SELECT * FROM client_package WHERE id = ?");
    $stmtDetails->bind_param('i', $Packageid);
    $stmtDetails->execute();
    $detailsResult = $stmtDetails->get_result();
    
     if ($detailsRow = $detailsResult->fetch_assoc()) {
        $receiverEmail = $detailsRow["Receiver_Email"];
        $qrCodeText = $detailsRow["QrCodeText"];
        $PackageName = $detailsRow['Package_Name'];
        $clientID = $detailsRow["Client_ID"];
        $PackageidNum = $detailsRow['PackageNameCode'];
        // $QRText = $GetclientIdrow['QrCodeText'];

    } else {
        echo "Error fetching package details.";
        exit;
    }
    $stmtDetails->close();

            $stmtEmail = $conn->prepare("SELECT Email FROM account WHERE id = ?");
            $stmtEmail->bind_param('i', $clientID);
            $stmtEmail->execute();
            $emailResult = $stmtEmail->get_result();
            
            if ($emailRow = $emailResult->fetch_assoc()) {
                $clientEmail = $emailRow["Email"];
            } else {
                echo "Error fetching client's email.";
                exit;
            }
            $stmtEmail->close();

    $stmtUpdate = $conn->prepare("UPDATE package_transaction SET Delay_Confirmation = ?, Delay_Date = NOW() WHERE Package_id = ?");
    $stmtUpdate->bind_param('si', $DelayConfirm, $Packageid);

    if (!$stmtUpdate->execute()) {
        echo "Failed to update: " . $stmtUpdate->error;
        $stmtUpdate->close();
        exit;
    }
    $stmtUpdate->close();
    $websiteLink = "http://localhost/masbatetrucking/index.php?redirect=transactionrecord"; 
    $emailSubject = "Package Has Been Delay";
    $emailBody = "Dear Client, 

    We regret to inform you that your package with OTS: '".$PackageidNum."' has been delayed. We apologize for any inconvenience this may have caused. Thank you for your understanding.
    
    Best regards,
    Masbate Trucking <br><br> Check out your Transaction on this site: <a href='$websiteLink'>$websiteLink</a>";
    $emailClientResponse = sendEmailNotification($clientEmail, $emailSubject, $emailBody);

    
    // echo "<script> alert('Confirm Successfully'); window.location = '../StaffPackageTransactionStatus.php'
    // </script>";

    $successMessage = "Send Successfully.";
        header("Location: ../StaffPackageTransactionStatus.php?success_message=" . urlencode($successMessage));
        exit;

}

elseif(isset($_POST["Package_Update3"])) {
    $Packageid = $_POST["idtransaction"];
    $Delivered = $_POST["Delivered"];
    $arrive_delivered = $_POST["arrive_delivered"];

    $imagePath = null;

    if (isset($_FILES['imageFile']) && $_FILES['imageFile']['error'] === UPLOAD_ERR_OK) {
        $targetPath = '../ProofOfPackage/';
        $tmp_name = $_FILES['imageFile']['tmp_name'];
        $name = basename($_FILES['imageFile']['name']);
        $targetFile = $targetPath . $name;

        if (move_uploaded_file($tmp_name, $targetFile)) {
            echo "File uploaded successfully.<br>";
            $imagePath = $targetFile;
        } else {
            echo "Failed to move uploaded file.<br>";
            exit;
        }
    } else {
        echo "No file uploaded or an error occurred.<br>";
    }

    $stmtUpdate = $conn->prepare("UPDATE package_transaction SET  Delivered = ?, Delivered_DateTime =NOW(), Proof_Received = ? WHERE Package_id = ?");
   
    $stmtUpdate->bind_param('ssi', $Delivered, $imagePath, $Packageid);

    
    $stmtDetails = $conn->prepare("SELECT * FROM client_package WHERE id = ?");
    $stmtDetails->bind_param('i', $Packageid);
    $stmtDetails->execute();
    $detailsResult = $stmtDetails->get_result();

    if ($detailsRow = $detailsResult->fetch_assoc()) {
        $clientID = $detailsRow["Client_ID"];
        $PackageName = $detailsRow['Package_Name'];
        $qrCodeText = $detailsRow["QrCodeText"];
        $PackageidNum = $detailsRow['PackageNameCode'];

    } else {
        echo "Error fetching package details.";
        exit;
    }
    $stmtDetails->close();

   

    $stmtEmail = $conn->prepare("SELECT Email FROM account WHERE id = ?");
    $stmtEmail->bind_param('i', $clientID);
    $stmtEmail->execute();
    $emailResult = $stmtEmail->get_result();

    if ($emailRow = $emailResult->fetch_assoc()) {
        $clientEmail = $emailRow["Email"];
    } else {
        echo "Error fetching client's email.";
        exit;
    }

    $stmtEmail->close();
   
    if ($stmtUpdate->execute()) {

        $stmt = $conn->prepare("UPDATE client_package SET  Arriving_Package = ? WHERE id = ?");
       
        $stmt->bind_param('si', $arrive_delivered, $Packageid);
    
      
        if ($stmt->execute()) {
      
        //   window.location = '../StaffPackageTransactionStatus.php'</script>";
        $websiteLink = "http://localhost/masbatetrucking/index.php?redirect=transactionrecord"; 
        $emailSubject = "Package Has Been Received";
        $emailBody = "Dear Client, Your package OTS: '".$PackageidNum."' has been Receive by the client Receiver. Thank you! <br><br> Check out your Transaction on this site: <a href='$websiteLink'>$websiteLink</a>";
        $emailClientResponse = sendEmailNotification($clientEmail, $emailSubject, $emailBody);
        
        $successMessage = "Delivered Successfully.";
                header("Location: ../StaffPackageTransactionStatus.php?success_message=" . urlencode($successMessage));
                exit;
      
        } else {
            echo "Statement execution failed: " . $stmt->error;
        }
     
    } else {
        echo "Statement execution failed: " . $stmtUpdate->error;
    }
}
else {
    echo "Form has not been submitted";
}

?>
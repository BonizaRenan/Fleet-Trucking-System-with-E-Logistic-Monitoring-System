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

    if ($attachment) {
        $mail->addStringAttachment($attachment, 'qrcode.png', 'base64', 'image/png');
    }

    if(!$mail->send()) {
        return 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        return true;
    }
}

if (isset($_POST["Package_Update"])) {
    $packageID = $_POST["id"];
    $ArrivingPackage = $_POST["ArrivingPackage"];
    $Onsite_receiver = $_POST["Onsite_Receiver"];

    $stmtUpdate = $conn->prepare("UPDATE client_package SET  Arriving_Package=? , Onsite_Receiver_Name= ? WHERE id=?");
    $stmtUpdate->bind_param('ssi',  $ArrivingPackage, $Onsite_receiver, $packageID);

    $GetclientId = $conn->prepare("SELECT * FROM client_package WHERE id=?");
    $GetclientId->bind_param('i', $packageID);
    $GetclientId->execute();
    $GetclientIdResult = $GetclientId->get_result();
    $GetclientIdrow = $GetclientIdResult->fetch_assoc();
    $clientID = $GetclientIdrow['Client_ID'];
    $PackageidNum = $GetclientIdrow['PackageNameCode'];

    $stmtEmail = $conn->prepare("SELECT Email FROM account WHERE id=?");
    $stmtEmail->bind_param('i',$clientID);
    $stmtEmail->execute();
    $emailResult = $stmtEmail->get_result();
    $emailRow = $emailResult->fetch_assoc();

    $stmtQR = $conn->prepare("SELECT QrCodeText FROM client_package WHERE id=?");
    $stmtQR->bind_param('i', $packageID);
    $stmtQR->execute();
    $qrResult = $stmtQR->get_result();
    $qrRow = $qrResult->fetch_assoc();
    
    $imageData = generateQRBase64($qrRow["QrCodeText"]);
    $PackageName = $GetclientIdrow['Package_Name'];
    
    $emailBody = "Here is your QR code for the package of your OTS: '". $PackageidNum ."'. Please find the attachment for the QR code.";

    if ($stmtUpdate->execute()) {
        // echo "<script> alert('Update Successfully');
        // window.location = '../AdminInventoryPackage.php'</script>";

        $emailResponse = sendEmailNotification($emailRow["Email"], "Your Package QR Code", $emailBody, $imageData);

        $successMessage = "Package Accepted Successfully.";
                header("Location: ../AdminInventoryPackage.php?success_message=" . urlencode($successMessage));
                exit;
        $stmtUpdate->close();
    } else {
        echo "Failed to insert: " . $stmtUpdate->error;
        $stmtUpdate->close();
        exit;
    }
}

?>
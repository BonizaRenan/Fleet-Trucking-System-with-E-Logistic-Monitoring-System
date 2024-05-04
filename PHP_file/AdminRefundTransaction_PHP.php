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

if (isset($_POST["submit_Refund"])) {
    $packageID = $_POST["id"];
    $paymentConfirmationRefund = 'Refund';
    $clientpaymentConfirmationRefund = 'Confirm';

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

    $imagePath = null;

    if (isset($_FILES['imageFile']) && $_FILES['imageFile']['error'] === UPLOAD_ERR_OK) {
        $targetPath = '../Payment_Screenshots/';
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

    $query = "UPDATE payment_transaction SET Refund_Transaction_SS='$imagePath', Refund_Confirmation='$clientpaymentConfirmationRefund' WHERE Package_ID='$packageID'";
    $Status = mysqli_query($conn, $query);

    if ($Status) {
        $query1 = "UPDATE client_package SET Payment_Confirmation='$paymentConfirmationRefund',Arriving_Package='$paymentConfirmationRefund' WHERE id='$packageID'";
        $Status1 = mysqli_query($conn, $query1);
        if ($Status1) {
        $websiteLink = "http://localhost/masbatetrucking/index.php?redirect=transactionrecord"; 
        $emailSubject = "Refund Payment";
                $emailBody = "Dear Client, your OTS: '".$PackageidNum."'. Refunded Successfully. Thank you! <br><br> Check out your Transaction on this site: <a href='$websiteLink'>$websiteLink</a>"
                ;
                sendEmailNotification($emailRow['Email'], $emailSubject, $emailBody);
                
                $successMessage = "Refunded Successfully.";
                header("Location:../AdminRefundPaymentTransaction.php?success_message=" . urlencode($successMessage));
                exit;
        }
        else {
            echo "Failed to execute SQL statement.<br>";
            exit;
        }
    } else {
        echo "Failed to execute SQL statement.<br>";
        exit;
    }
} else {
    echo "Form not submitted.<br>";
    exit;
}
 

?>
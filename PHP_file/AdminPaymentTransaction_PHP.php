
<?php
$conn = mysqli_connect("localhost", "root", "", "transaction");
$receiptNumber = rand(100000, 999999);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
function generatePDFReceipt($packageID, $receiptNumber) {
    require_once('../tcpdf/tcpdf.php');
    
    global $conn;
    
    $sqlDetails = "SELECT * FROM client_package WHERE id=?";
    $stmt = $conn->prepare($sqlDetails);
    $stmt->bind_param('i', $packageID);
    $stmt->execute();
    $detailsResult = $stmt->get_result();
    $detailsRow = $detailsResult->fetch_assoc();

    $sqlpayment = "SELECT * FROM payment_transaction WHERE Package_ID=?";
    $stmt2 = $conn->prepare($sqlpayment);
    $stmt2->bind_param('i', $packageID);
    $stmt2->execute();
    $paymentResult = $stmt2->get_result();
    $paymentRow = $paymentResult->fetch_assoc();

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle('Payment Receipt');
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetMargins(10, 10, 10);
    $pdf->AddPage();

    $originalPrice = $paymentRow["Price_Payment"];
  
    // // function calculateVAT($originalPrice, ) {
    // //     return ($originalPrice / 1.12 ) * 0.12;
    // // }

    $vatAmount = ($originalPrice / 1.12 ) * 0.12;

    $html = '
    <table cellpadding="5" cellspacing="0" style="width: 100%; border: 1px solid #000;">
    <tr>
        <td colspan="2" style="text-align: center; font-size: 24px; font-weight: bold; padding-bottom: 5px;">Masbate Trucking</td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center; font-size: 8px; font-weight: bold; padding-top: 0px;">Address: 312 cordero st. 8th ave caloocan city</td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center; font-size: 8px; font-weight: bold; padding-top: 5px; border-bottom: 1px dashed #000;">Contact Number: 09219775000</td>
    </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-size: 20px; margin-top: 10px; border-bottom: 1px dashed #000;">Payment Receipt</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Ref No:</td>
            <td style="text-align: right;">' . $receiptNumber . '</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Date:</td>
            <td style="text-align: right;">' . $detailsRow["Date_Time"] . '</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Package Id:</td>
            <td style="text-align: right;">' . strtoupper($detailsRow["PackageNameCode"]) . '</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">12% VAT:</td>
            <td style="text-align: right;">' . number_format($vatAmount , 2) . '</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Amount:</td>
            <td style="text-align: right;">' . number_format($paymentRow["Price_Payment"], 2) . '</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Paid By:</td>
            <td style="text-align: right;">' . $detailsRow["Client_name"] . '</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Payment Method:</td>
            <td style="text-align: right;">' . $detailsRow["Payment_Method"] . '</td>
        </tr>
        <tr>
        <td colspan="2" style="border-bottom: 1px dashed #000; line-height: 5px;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; padding-top: 20px; padding-bottom: 10px;">Thank you for your payment!</td>
        </tr>
    </table>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdfPath = __DIR__ . "/Receipt/receipt_$packageID.pdf";
    $pdf->Output($pdfPath, 'F');

    return $pdfPath;
}

function sendEmailNotification($toEmail, $subject, $body, $attachmentPath) {
    require '../PHPMailer/PHPMailer.php';
    require '../PHPMailer/SMTP.php';
    require '../PHPMailer/Exception.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->addAttachment($attachmentPath);
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
    } else {
        unlink($attachmentPath); 
    }
}

if (isset($_POST["ConfirmationPayment"])) {
    $packageID = $_POST["id"];
    $paymentConfirmation = $_POST["paymentConfirmation"];
    $ArrivingPackage = $_POST["ArrivingPackage"];
    $pdfPath = generatePDFReceipt($packageID, $receiptNumber);
    

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

    $sqlpayment = "SELECT * FROM payment_transaction WHERE Package_ID=?";
    $stmt2 = $conn->prepare($sqlpayment);
    $stmt2->bind_param('i', $packageID);
    $stmt2->execute();
    $paymentResult = $stmt2->get_result();
    $paymentRow = $paymentResult->fetch_assoc();

    $originalPrice = $paymentRow["Price_Payment"];
    $vatAmount = ($originalPrice / 1.12 ) * 0.12;

    $stmtUpdate = $conn->prepare("UPDATE client_package SET Payment_Confirmation=?, Arriving_Package=? WHERE id=?");
    $stmtUpdate->bind_param('ssi', $paymentConfirmation, $ArrivingPackage, $packageID);

   

    if ($stmtUpdate->execute()) {

        $stmtUpdate2 = $conn->prepare("UPDATE payment_transaction SET OR_No=?, VAT = ? WHERE package_ID=?");
        $stmtUpdate2->bind_param('ssi',$receiptNumber, $vatAmount, $packageID);
        if ($stmtUpdate2->execute()) {
            // echo "<script>
            // alert('Accept Successfully');
            // window.location = '../AdminPaymentTransaction.php';
            // </script>";
        
        $websiteLink = "http://localhost/masbatetrucking/index.php?redirect=transactionrecord"; 
        $emailSubject = "Payment Transaction";
        $emailBody = "Dear Client, your Payment has been accepted successfully.You may now deliver your package OTS: '".$PackageidNum."' on the site. Thank you! <br><br> Check out your Transaction on this site: <a href='$websiteLink'>$websiteLink</a>";
        sendEmailNotification($emailRow['Email'], $emailSubject, $emailBody, $pdfPath);

        $successMessage = "Payment Accepted Successfully.";
                header("Location: ../AdminPaymentTransaction.php?success_message=" . urlencode($successMessage));
                exit;
        }else {
            echo "Failed to insert: " . $stmtUpdate2->error;
            $stmtUpdate2->close();
            exit;
        }
    
        $stmtUpdate->close();
    } else {
        echo "Failed to insert: " . $stmtUpdate->error;
        $stmtUpdate->close();
        exit;
    }


}



?>
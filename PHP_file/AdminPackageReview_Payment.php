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
    $Decision = $_POST["Decision"];
    $PaymentPrice = $_POST["PaymentPrice"];
    $Client_PaymentConfirmation = $_POST["Client_PaymentConfirmation"];
    $clientDecision = $_POST["clientDecision"];
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

    // Use a prepared statement to prevent SQL injection
    $stmtUpdate = $conn->prepare("UPDATE client_package SET Status_Review=? WHERE id=?");
    $stmtUpdate->bind_param('si', $Decision, $packageID);
    $successUpdate = $stmtUpdate->execute();
    $stmtUpdate->close();

    $paymentterms = "SELECT Payment_Method FROM client_package WHERE id=? ";
    $stmt = $conn->prepare($paymentterms);
    $stmt->bind_param('i', $packageID);
    $stmt->execute();
    $paymenttermsresult = $stmt->get_result();
    $paymenttermsfetch = $paymenttermsresult->fetch_assoc();
    $stmt->close();

    if ($successUpdate) {
        $paymentMethod = $paymenttermsfetch['Payment_Method'];

        if ($paymentMethod == "Online Payment") {
            $stmt1 = $conn->prepare("INSERT INTO payment_transaction (Package_ID, Price_Payment, Payment_Desc, Client_Confirm) VALUES (?, ?, ?, ?)");
            $stmt1->bind_param("isss", $packageID, $PaymentPrice, $PaymentPriceDesc, $Client_PaymentConfirmation);

            if ($stmt1->execute()) {

                       $emailSubject = "Package Review";
                        $emailBody = "Dear Client, your online payment package OTS: '".$PackageidNum."' has been accepted successfully. Thank you!";
                        sendEmailNotification($emailRow['Email'], $emailSubject, $emailBody);

                        $successMessage = "Package Accepted Successfully.";
                        header("Location: ../AdminPackageReview.php?success_message=" . urlencode($successMessage));
                        exit;

                $stmt1->close();
            } else {
                echo "Failed to insert: " . $stmt1->error;
                $stmt1->close();
                exit;
            }
            
        } elseif ($paymentMethod == "On-Site Payment") {

                $stmt2 = $conn->prepare("INSERT INTO payment_transaction (Package_ID, Price_Payment, Payment_Desc , Client_Confirm) VALUES (?, ?, ?, ?)");
                $stmt2->bind_param("isss", $packageID, $PaymentPrice, $PaymentPriceDesc, $Client_PaymentConfirmation);
                
                if ($stmt2->execute()) {
                        $websiteLink = "http://localhost/masbatetrucking/index.php?redirect=transactionrecord"; 
                        $emailSubject = "Package Review";
                        $emailBody = "Dear Client, your on-site payment package OTS: '".$PackageidNum."' has been accepted successfully. Thank you! <br><br> Check out your Transaction on this site: <a href='$websiteLink'>$websiteLink</a>";
                        sendEmailNotification($emailRow['Email'], $emailSubject, $emailBody);
                    
                        $successMessage = "Package Accepted Successfully.";
                        header("Location: ../AdminPackageReview.php?success_message=" . urlencode($successMessage));
                        exit;
                    
                    $stmt2->close();
                } else {
                    echo "Failed to insert: " . $stmt2->error;
                    $stmt2->close();
                    exit;
                }

          
            // echo "On-Site Payment chosen.";
        }
    } else {
        // The query failed
        echo "Error: " . mysqli_error($conn);
    }


}

?>
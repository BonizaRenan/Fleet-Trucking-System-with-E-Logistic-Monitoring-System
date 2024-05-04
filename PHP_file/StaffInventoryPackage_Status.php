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

if (isset($_POST["Package_Update"])) {
    $Staff_id = $_POST["Staff_id"];
    $Packageid = $_POST["PackageID"];
    $Staff_name = $_POST["Staff_name"];
    $truck_Num = $_POST["truck_Num"];
    $On_Going = $_POST["On_Going"];
    $Arriving_Masbate = $_POST["Arriving_Masbate"];
    $Status_Package = $_POST["Status_Package"];

  
    $currentDate = new DateTime();

  
    $estimatedDate = $currentDate->modify('+4 days');

    // Format the estimated date as a string in the "YYYY-MM-DD" format
    $estimatedDateString = $estimatedDate->format("Y-m-d");




    $GetclientId = $conn->prepare("SELECT * FROM client_package WHERE id=?");
    $GetclientId->bind_param('i', $Packageid);
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

    $stmtUpdate = $conn->prepare("UPDATE client_package SET Arriving_Package=? WHERE id=?");
    $stmtUpdate->bind_param('si', $Status_Package, $Packageid);
    
    if ($stmtUpdate->execute()) {
        $stmt1 = $conn->prepare("INSERT INTO package_transaction (Package_ID, Staff_id ,Staff_Name, Truck_PlateNumber, Estimated_Date, On_Going, On_Going_DateTime, Arrive_Masbate, Delivered) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?)");
        $stmt1->bind_param("isssssss", $Packageid, $Staff_id ,$Staff_name, $truck_Num, $estimatedDateString, $On_Going, $Arriving_Masbate, $Arriving_Masbate);

            if ($stmt1->execute()) {
                // echo "<script> alert('Confirm Successfully');
                // window.location = '../StaffInventoryPackage.php'</script>";
                $websiteLink = "http://localhost/masbatetrucking/index.php?redirect=transactionrecord"; 
                $emailSubject = "Your package OTS: '".$PackageidNum."' is on its way to the Masbate site";
                $emailBody = "Dear Client, your package OTS: '".$PackageidNum."' is on its way to the Masbate site. Thank you! <br><br> Check out your Transaction on this site: <a href='$websiteLink'>$websiteLink</a>";
                sendEmailNotification($emailRow['Email'], $emailSubject, $emailBody);

                $successMessage = "Confirmed Successfully.";
                header("Location: ../StaffInventoryPackage.php?success_message=" . urlencode($successMessage));
                exit;

                $stmt1->close();
            } else {
                echo "Failed to insert: " . $stmt1->error;
                $stmt1->close();
                exit;
            }
       
    } else {
        echo "Failed to insert: " . $stmtUpdate->error;
        $stmtUpdate->close();
        exit;
    }
}

?>
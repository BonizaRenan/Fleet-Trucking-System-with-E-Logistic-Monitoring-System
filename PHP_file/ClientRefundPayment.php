<?php
$conn = mysqli_connect("localhost", "root", "", "transaction");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST["submit_Refund"])) {
    $packageID = $_POST["id"];
    $paymentConfirmationRefund = 'Refund';

   
    $RefundType = isset($_POST["Payment_type"]) ? $_POST["Payment_type"] : null;
    $RefundDesc = $_POST["RefundPaymentDesc"];


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

    $query = "UPDATE payment_transaction SET Refund_Reason='$RefundDesc', Refund_Type= '$RefundType', Refund_Image = ' $imagePath', Refund_Confirmation ='$paymentConfirmationRefund' WHERE Package_ID='$packageID'";
    $Status = mysqli_query($conn, $query);

    if ($Status) {
        $successMessage = "Refund Being Processed";
        header("Location: ../ClientPackageInfo.php?Transaction_ID=" . $packageID . "&success_message=" . urlencode($successMessage));
        exit;
    } else {
      
        echo "Failed to execute SQL statement.<br>";
        exit;
    }
} else {
 
    echo "Form not submitted.<br>";
    exit;
}
?>

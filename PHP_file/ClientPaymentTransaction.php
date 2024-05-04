<?php
$conn = mysqli_connect("localhost", "root", "", "transaction");

if (isset($_POST["submit_Payment"])) {
    $TransactionPaymentID = $_POST["TransactionPaymentID"];
    $Payment_type = $_POST["Payment_type"];
    $Client_PaymentConfirmation = $_POST["Client_PaymentConfirmation"];
    $Admin_PaymentConfirmation = $_POST["Admin_PaymentConfirmation"];
    $imagePending = 'Pending';

    if (isset($_FILES['imageFiles'])) {
        $targetPath = '../Payment_Screenshots/';

        foreach ($_FILES['imageFiles']['error'] as $key => $error) {
            if ($error === UPLOAD_ERR_OK) {
                $tmp_name = $_FILES['imageFiles']['tmp_name'][$key];
                $name = basename($_FILES['imageFiles']['name'][$key]);
                $targetFile = $targetPath . $name;

                if (move_uploaded_file($tmp_name, $targetFile)) {
                    echo "File uploaded successfully.<br>";
                    $imagePath = $targetFile;
                    
                
                    $stmt = $conn->prepare("UPDATE payment_transaction SET Client_Confirm = ?, Payment_Type = ? WHERE Package_ID = ?");
                    $stmt->bind_param('ssi', $Client_PaymentConfirmation, $Payment_type, $TransactionPaymentID);
                    
                    if ($stmt->execute()) {
                        $stmt1 = $conn->prepare("INSERT INTO payment_screenshots (Package_Id, Screenshot_Payment, Status_image) VALUES (?, ?, ?)");
                        $stmt1->bind_param('sss', $TransactionPaymentID, $imagePath, $imagePending);

                        if ($stmt1->execute()) {
                            $stmt2 = $conn->prepare("UPDATE client_package SET Payment_Confirmation = ? WHERE id = ?");
                            $stmt2->bind_param('si', $Admin_PaymentConfirmation, $TransactionPaymentID);

                            if ($stmt2->execute()) {
                                $successMessage = "Payment Successfully Processed.";
                                header("Location: ../ClientPackageInfo.php?Transaction_ID={$TransactionPaymentID}&success_message=" . urlencode($successMessage));
                                exit;
                            } else {
                                echo "Failed to execute: " . $stmt2->error . "<br>";
                                $stmt2->close();
                                exit;
                            }
                        } else {
                            echo "Failed to execute: " . $stmt1->error . "<br>";
                            $stmt1->close();
                            exit;
                        }
                    } else {
                        echo "Failed to execute: " . $stmt->error . "<br>";
                        $stmt->close();
                        exit;
                    }
                } else {
                    echo "Failed to upload file.<br>";
                    exit;
                }
            }
        }
    }
}
if (isset($_POST["Cancel_Payment"])) { 
    $TransactionPaymentID = $_POST["TransactionPaymentID"];
    $Client_PaymentConfirmation = "Decline";

    $stmt = $conn->prepare("UPDATE payment_transaction SET Client_Confirm = ? WHERE Package_ID = ?");
    $stmt->bind_param('si',$Client_PaymentConfirmation, $TransactionPaymentID);
    
    if ($stmt->execute()) {
        // echo "<script> alert('Decline Successfully'); window.location = '../ClientDashboard.php'</script>";
        $successMessage = "Cancel Successfully.";
            header("Location: ../ClientPackageInfo.php?Transaction_ID={$TransactionPaymentID}&success_message=" . urlencode($successMessage));
            exit;
           
    } else {
        echo "Failed to execute: " . $stmt->error . "<br>";
        $stmt->close();
        exit;
    }

}

if (isset($_POST["Onsite_Accept"])) { 
    $TransactionPaymentID = $_POST["TransactionPaymentID"];
    $Admin_PaymentConfirmation = $_POST["Admin_PaymentConfirmation"];
    $Client_PaymentConfirmation = $_POST["Client_PaymentConfirmation"];

    $stmt = $conn->prepare("UPDATE payment_transaction SET Client_Confirm = ? WHERE Package_ID = ?");
    $stmt->bind_param('si',$Client_PaymentConfirmation, $TransactionPaymentID);
    
    if ($stmt->execute()) {
        
        $stmt2 = $conn->prepare("UPDATE client_package SET Payment_Confirmation	 = ? WHERE id = ?");
        $stmt2->bind_param('si',$Admin_PaymentConfirmation, $TransactionPaymentID);
            if ($stmt2->execute()) {
            // echo "Query executed successfully.<br>";
            // echo "<script> alert('Accept Successfully'); window.location = '../ClientDashboard.php'</script>";
            $successMessage = "Accepted Successfully.";
            header("Location: ../ClientPackageInfo.php?Transaction_ID={$TransactionPaymentID}&success_message=" . urlencode($successMessage));
            exit;
            
            }
            else {
            echo "Failed to execute: " . $stmt2->error . "<br>";
            $stmt2->close();
            exit;
            }
    } else {
        echo "Failed to execute: " . $stmt->error . "<br>";
        $stmt->close();
        exit;
    }

}

if (isset($_POST["Onsite_Decline"])) { 
    $TransactionPaymentID = $_POST["TransactionPaymentID"];
    $Client_PaymentConfirmation = "Decline";

    $stmt = $conn->prepare("UPDATE payment_transaction SET Client_Confirm = ? WHERE Package_ID = ?");
    $stmt->bind_param('si',$Client_PaymentConfirmation, $TransactionPaymentID);
    
    if ($stmt->execute()) {
        // echo "<script> alert('Decline Successfully'); window.location = '../ClientDashboard.php'</script>";
        $successMessage = "Decline Successfully.";
            header("Location: ../ClientPackageInfo.php?Transaction_ID={$TransactionPaymentID}&success_message=" . urlencode($successMessage));
            exit;
           
    } else {
        echo "Failed to execute: " . $stmt->error . "<br>";
        $stmt->close();
        exit;
    }

}

if (isset($_POST["Onsite_ReadyPay"])) { 
    $TransactionPaymentID = $_POST["TransactionPaymentID"];
    $Client_PaymentConfirmation = "Pending";

    $stmt = $conn->prepare("UPDATE client_Package SET Payment_Confirmation = ? WHERE id = ?");
    $stmt->bind_param('si',$Client_PaymentConfirmation, $TransactionPaymentID);
    
    if ($stmt->execute()) {
        // echo "<script> alert('Decline Successfully'); window.location = '../ClientDashboard.php'</script>";
        $successMessage = "Accept Successfully.";
            header("Location: ../ClientPackageInfo.php?Transaction_ID={$TransactionPaymentID}&success_message=" . urlencode($successMessage));
            exit;
           
    } else {
        echo "Failed to execute: " . $stmt->error . "<br>";
        $stmt->close();
        exit;
    }


}

if (isset($_POST["Cancel_Refund_online"])) {
    $TransactionPaymentID = $_POST["TransactionPaymentID"];
    $Client_CancelRefund = "Cancel";

    $stmt = $conn->prepare("UPDATE payment_transaction SET Refund_Confirmation = ? WHERE package_ID = ?");
    $stmt->bind_param('si',$Client_CancelRefund, $TransactionPaymentID);
    
    if ($stmt->execute()) {

        $successMessage = "Cancel Successfully.";
            header("Location: ../ClientPackageInfo.php?Transaction_ID={$TransactionPaymentID}&success_message=" . urlencode($successMessage));
            exit;
           
    } else {
        echo "Failed to execute: " . $stmt->error . "<br>";
        $stmt->close();
        exit;
    }

}

if (isset($_POST["Cancel_Refund_Onsite"])) {
    $TransactionPaymentID = $_POST["TransactionPaymentID"];
    $Client_CancelRefund = "Cancel";

    $stmt = $conn->prepare("UPDATE payment_transaction SET Refund_Confirmation = ? WHERE package_ID = ?");
    $stmt->bind_param('si',$Client_CancelRefund, $TransactionPaymentID);
    
    if ($stmt->execute()) {

        $successMessage = "Cancel Successfully.";
            header("Location: ../ClientPackageInfo.php?Transaction_ID={$TransactionPaymentID}&success_message=" . urlencode($successMessage));
            exit;
           
    } else {
        echo "Failed to execute: " . $stmt->error . "<br>";
        $stmt->close();
        exit;
    }

}
?>

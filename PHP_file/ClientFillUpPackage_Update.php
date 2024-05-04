<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$account = mysqli_connect("localhost", "root", "", "transaction");
if ($account->connect_error) {
    die('Connection to account_info failed: ' . $account->connect_error);
}

$stmt = $account->prepare("SELECT * FROM account WHERE id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$Fullname = "";
$Sender_MobileNumber = "";
$Sender_Email = "";

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $Fullname = $row['Firstname'] . ' ' . $row['Lastname'];
    $Sender_MobileNumber = $row['Phone_Num'];
    $Sender_Email = $row['Email'];
}
$stmt->close();

$conn = mysqli_connect("localhost", "root", "", "transaction");
if ($conn->connect_error) {
    die('Connection to transaction failed: ' . $conn->connect_error);
}

if (isset($_POST["submit"])) {
    $transactionpackageid = $_POST["TransactionpackageID"];
    $PackageName = $_POST["PackageName"];

    if ($_POST['PackageTypeDropdown'] == 'Other') {
        $PackageType = $_POST['AdditionalPackageType'];
    } else {
        $PackageType = $_POST['PackageTypeDropdown'];
    }

    $PackageWeight = $_POST["PackageWeight"];
    $PackageQuantity = $_POST["PackageQuantity"];
    $RecieverFirstName = $_POST["ReceiverFn"];
    $ReceiverLastname = $_POST["ReceiverSn"];
    $MobileNumber = $_POST["MobileNumber"];
    $Email = $_POST["Email"];
    $PackageDestination = $_POST["Address"];
    $PaymentMethod = $_POST["payment"];
    $QrcodeText = $_POST["qrCodeText"];
    $Status_Review = $_POST["Status_Review"];
    $DeclaredValue = $_POST["DeclaredPrice"];

    $RecieverName = $RecieverFirstName . " " . $ReceiverLastname;
    $PackageNameCodeId = $PackageName . "" . $QrcodeText;

   
    $stmt1 = $conn->prepare("UPDATE client_package SET Package_Name = ?, Package_Type = ?, Package_Weight = ?, Package_Quantity = ?, DeclaredValuePrice = ?, Receiver_Name = ?, Receiver_PhoneNum = ?, Receiver_Email = ?, Receiver_Address = ?, Payment_Method = ?, QrCodeText = ?, Status_Review = ? WHERE id = ?");
    $stmt1->bind_param("ssssssssssssi", $PackageName, $PackageType, $PackageWeight, $PackageQuantity, $DeclaredValue, $RecieverName, $MobileNumber, $Email, $PackageDestination, $PaymentMethod, $QrcodeText, $Status_Review, $transactionpackageid);

    if ($stmt1->execute()) {
        $stmt1->close();

   
        if (!empty($_FILES['imageFiles']['name'][0])) {
         
            $stmtDeleteImages = $conn->prepare("DELETE FROM package_image WHERE Package_ID = ?");
            $stmtDeleteImages->bind_param('i', $transactionpackageid);
            
            $targetPath = '../package_Image/';
            if ($stmtDeleteImages->execute()) {
                $stmtDeleteImages->close();

          
                for ($i = 0; $i < count($_FILES['imageFiles']['name']); $i++) {  
                    $imageFile = $_FILES['imageFiles']['tmp_name'][$i];  
                    $targetFile = $targetPath . basename($_FILES['imageFiles']['name'][$i]);

                    if (move_uploaded_file($imageFile, $targetFile)) {
                        $imagePath = $targetFile;

                      
                        $stmtInsertImage = $conn->prepare("INSERT INTO package_image (Package_ID, Package_Image) VALUES (?, ?)");
                        $stmtInsertImage->bind_param('is', $transactionpackageid, $imagePath);

                        if ($stmtInsertImage->execute()) {
                            $imageId = $conn->insert_id; 
                            echo "Image ID: " . $imageId . " - Image Path: " . $imagePath . "<br>";
                        } else {
                            echo "Failed to insert new image: " . $stmtInsertImage->error;
                            $stmtInsertImage->close();
                            exit;
                        }
                    } else {
                        echo "Failed to move uploaded file. Check directory permissions and file name uniqueness.";
                        exit;
                    }
                }

             
                $stmtInsertImage->close();
            } else {
                echo "Failed to delete existing images: " . $stmtDeleteImages->error;
                $stmtDeleteImages->close();
                exit;
            }
        }

     
        echo "<script> alert('Updated Successfully'); window.location = '../ClientDashboard.php'</script>";
    } else {
        echo "<script> alert('Details not provided'); window.location = '../ClientDashboard.php'</script>";
    }

    $conn->close();
}
?>

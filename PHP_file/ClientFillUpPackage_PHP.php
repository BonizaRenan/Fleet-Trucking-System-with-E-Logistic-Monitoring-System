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

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $Fullname = $row['Firstname'] . ' ' . $row['Lastname'];
    $Sender_MobileNumber = $row ['Phone_Num'];
    $Sender_Email = $row ['Email'];
}
$stmt->close();

$conn = mysqli_connect("localhost", "root", "", "transaction");
if ($conn->connect_error) {
    die('Connection to transaction failed: ' . $conn->connect_error);
}

if (isset($_POST["submit"])) {
    $PackageName = $_POST["PackageName"];

    if ($_POST['PackageTypeDropdown'] == 'Other') {
        $PackageType = $_POST['AdditionalPackageType'];
    } else {
        $PackageType = $_POST['PackageTypeDropdown'];
    }
    
    $PackagWeight = $_POST["PackageWeight"];
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
    function generateRandomNumber() {
   
        $min = 10000000000;
        $max = 99999999999;
        
      
        $randomNumber = rand($min, $max);
    
        return $randomNumber;
    }
    
   
    $generatedNumber = generateRandomNumber();
    // $PackageNameCodeId = $PackageName."" .$QrcodeText;

    

    $stmt1 = $conn->prepare("INSERT INTO client_package (Client_ID, Client_name, Client_PhoneNum, Client_Email,PackageNameCode, Package_Name, Package_Type, Package_Weight, Package_Quantity, DeclaredValuePrice, Receiver_Name, Receiver_PhoneNum, Receiver_Email ,Receiver_Address, Payment_Method, QrCodeText, Status_Review, Date_Time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt1->bind_param("sssssssssssssssss", $user_id, $Fullname,$Sender_MobileNumber, $Sender_Email, $generatedNumber, $PackageName, $PackageType, $PackagWeight, $PackageQuantity,  $DeclaredValue, $RecieverName, $MobileNumber, $Email, $PackageDestination, $PaymentMethod, $QrcodeText, $Status_Review);

    if (isset($_FILES['imageFiles'])) { 

        $targetPath = '../package_Image/';
        if ($stmt1->execute()) {
            $lastPackageId = $conn->insert_id;  
            $stmt1->close();

            for ($i = 0; $i < count($_FILES['imageFiles']['name']); $i++) {  
                $imageFile = $_FILES['imageFiles']['tmp_name'][$i]; 
                $targetFile = $targetPath . basename($_FILES['imageFiles']['name'][$i]);
             

                if (move_uploaded_file($imageFile, $targetFile)) {

                    $imagePath = $targetFile;

                    $stmt2 = $conn->prepare("INSERT INTO package_image (Package_ID, Package_Image) VALUES (?, ?)");
                    $stmt2->bind_param('is', $lastPackageId, $imagePath);
                    
                    if ($stmt2->execute()) {
                        
                        echo "<script> alert('Registered Successfully');
                        window.location = '../ClientDashboard.php'</script>";
                    } 
                    else {
                        echo "Failed to insert: " . $stmt2->error;
                        $stmt2->close();
                        exit;
                }
                }
                else {
                    echo "<script> alert('No Image Uploaded');
                    window.location = '../ClientDashboard.php'</script>";
                }
            }
        }
        else {
            echo "Failed to insert: " . $stmt1->error;
            $stmt1->close();
            exit;
            }
    }
}
$conn->close();
?>
<?php
$conn = mysqli_connect("localhost", "root", "", "transaction");
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
if (isset($_POST["submit"])) {
    $PlateNumber = $_POST["TruckPlateNum"];
    $TruckType = $_POST["TruckType"];
    // $TruckSpace = $_POST["TruckSpace"];
    $TruckStatus = $_POST["TruckStatus"];

    $imageName = '';

    if (isset($_FILES["imageFiles5"]) && $_FILES["imageFiles5"]["error"] == 0) {
        $fileExtension = pathinfo($_FILES["imageFiles5"]["name"], PATHINFO_EXTENSION);
        $imageName = "../profile_Image/" . time() . "." . $fileExtension;
        move_uploaded_file($_FILES["imageFiles5"]["tmp_name"], $imageName);
    }

    $stmt = $conn->prepare("SELECT * FROM truck WHERE Truck_PlateNumber = ?");
    $stmt->bind_param("s", $PlateNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        echo "<script> alert('The Plate Number has been Already Register');
              window.location = '../AdminTruckInventory.php'</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO truck VALUES('', ?, ?, ?, ?)");
        $stmt->bind_param("ssss", $imageName, $PlateNumber, $TruckType, $TruckStatus);
        $stmt->execute();
        // echo "<script> alert('Registered Successfully');
        //       window.location = '../AdminTruckInventory.php'
        //       </script>";
        $successMessage = "Registered Successfully.";
                header("Location: ../AdminTruckInventory.php?success_message=" . urlencode($successMessage));
                exit;
    }
}
?>
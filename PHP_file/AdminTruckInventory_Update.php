<?php
$conn = mysqli_connect("localhost", "root", "", "transaction");

if (isset($_POST["update"])) {
    $id = $_POST["id"];
    $PlateNumber = $_POST["TruckPlateNum"];
    $TruckType = $_POST["TruckType"];
    // $TruckSpace = $_POST["TruckSpace"];
    $TruckStatus = $_POST["Truck_Status"];

    $imageName = "";

 
    if (isset($_FILES["imageFiles"]) && $_FILES["imageFiles"]["error"] == 0) {
        $targetDir = "../profile_image/";
        $imageName = basename($_FILES["imageFiles"]["name"]);
        $targetFilePath = $targetDir . $imageName;
        $imageFileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

      
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'bmp');
        if (in_array($imageFileType, $allowTypes)) {
       
            if (move_uploaded_file($_FILES["imageFiles"]["tmp_name"], $targetFilePath)) {
          
            } else {
                echo "<script> alert('Sorry, there was an error uploading your file.');
                window.location = '../AdminTruckInventory.php'</script>";
                exit;
            }
        } else {
            echo "<script> alert('Sorry, only JPG, JPEG, PNG, GIF, & BMP files are allowed.');
            window.location = '../AdminTruckInventory.php'</script>";
            exit;
        }
    }

    
    $duplicate = mysqli_query($conn, "SELECT * FROM truck WHERE Truck_PlateNumber = '$PlateNumber' AND Truck_Id != '$id'");
    if (mysqli_num_rows($duplicate) > 0) {
        echo "<script> alert('The Plate Number has been Already Register');
            window.location = '../AdminTruckInventory.php'</script>";
        exit;
    }

  
    if ($imageName !== "") {
      
        $query = "UPDATE truck SET Truck_Image='$imageName', Truck_PlateNumber='$PlateNumber', Truck_Type = '$TruckType', Truck_Status='$TruckStatus' WHERE Truck_Id = '$id'";
    } else {
     
        $query = "UPDATE truck SET Truck_PlateNumber='$PlateNumber', Truck_Type = '$TruckType', Truck_Status='$TruckStatus' WHERE Truck_Id = '$id'";
    }

    mysqli_query($conn, $query);
    // echo "<script> alert('Update Successfully');
    //     window.location = '../AdminTruckInventory.php'</script>";
    $successMessage = "Updated Successfully.";
    header("Location: ../AdminTruckInventory.php?success_message=" . urlencode($successMessage));
    exit;
}
?>

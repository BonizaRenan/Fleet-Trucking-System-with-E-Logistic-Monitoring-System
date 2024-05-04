<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "transaction"); 
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location: index.php');
    exit; 
}

    if (isset($_POST["logout_truck"])) {
          $Truck_Status = $_POST["Truck_Status"];
          $Truck_Num = $_POST["Truck_Num"]; 
          $Truck_id =$_POST["Truck_id"];
          $End_Use = $_POST["End_User"];

          $query = "UPDATE truck_record SET End_Use = ?, Out_Date_Time = NOW() WHERE id =?";
          $stmt = $conn->prepare($query);
          $stmt->bind_param('ss', $End_Use, $Truck_Num);
          $stmt->execute();

          if ($stmt->execute()) {
            // Process each Truck_Id
           $query = "UPDATE truck SET Truck_Status= '$Truck_Status' WHERE Truck_Id = '$Truck_id'";
           $result = mysqli_query($conn, $query);
           if($result){
                  echo "<script> alert('Registered Successfully for Truck ID: $Truck_Num'); 
                window.location = '../StaffDashboard.php'</script>";
          }
        }
        }
                            
?>
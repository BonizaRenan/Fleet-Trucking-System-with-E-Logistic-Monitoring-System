<?php 
   $conn = mysqli_connect("localhost", "root", "", "transaction");

   if (isset($_POST["submit_btn"])) {
    $Staff_id = $_POST["Staff_id"];
    $Truck_Id = $_POST["Truck_Id"]; 
    $Staff_name = $_POST["Staff_name"];
    $Truck_PlateNum = $_POST["Truck_PlateNum"];
    $On_Use = $_POST["On_Use"];
    $End_Use = $_POST["End_Use"];
    $Truck_Status = $_POST["Truck_Status"];
   
     // Process each Truck_Id
           $query = "UPDATE truck SET Truck_Status='$Truck_Status' WHERE Truck_Id = '$Truck_Id'";
           $result = mysqli_query($conn, $query);
   
           if($result){
               $stmt = $conn->prepare("INSERT INTO truck_record (User_Id, truck_Id, Staff_Name ,Truck_Num, On_Use, In_Date_Time, End_Use) VALUES (?, ?, ?, ?, ?, NOW(), ?)");
               $stmt->bind_param("ssssss", $Staff_id, $Truck_Id, $Staff_name, $Truck_PlateNum, $On_Use, $End_Use);
               if ($stmt->execute()) {
                   echo "<script> alert('Registered Successfully for Truck ID: $Truck_Id');
                   window.location = '../StaffDashboard.php'</script>";
               } else {
                 
                   echo "Error for Truck ID: $Truck_Id - " . $stmt->error;
               }
               $stmt->close();
           }
       
   }
?>
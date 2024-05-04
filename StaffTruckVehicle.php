<?php
session_start();
    $user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location: index.php');
    exit; 
}
$conn = mysqli_connect("localhost", "root", "", "transaction");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="StaffTruckVehicle.css">
    <title>Truck Inventory</title>
</head>
<body>

        <div class="TopNav">
                
                <div class="logoname">
                    <img src="logo/OTS Logo.png" alt="" >
                    <span>Masbate Trucking</span>
                </a>
                </div>    
                <div class="Selection">
                        <li><a href="Profile.php">Profile</a></li>
                        <li><a href="Logout.php">Logout</a></li>
                   
                </div>
               
        </div>
        <!-- End of Nav Bar -->



        <!-- Search bar Start -->

        <div class="Search">
            <input type="text" class="Search_Input" id="search_input" placeholder="Search">
            <input type="button" class="Search_btn "value="Search">
        </div>
         <!-- Search bar End -->

         <script>
        document.querySelector(".Search_btn").addEventListener("click", function() {
        const searchQuery = document.getElementById('search_input').value;
        window.location.href = window.location.pathname + '?search=' + encodeURIComponent(searchQuery);
        });
        </script>
        <?php
         // Default SQL statement
         $sql = "SELECT * FROM truck";

       
         if (isset($_GET['search'])) {
          
             $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);

         
             $sql .= " WHERE (Truck_PlateNumber LIKE '%$searchTerm%' OR Truck_Type LIKE '%$searchTerm%' OR Truck_Status LIKE '%$searchTerm%')";
         }

    $trucks = $conn->query($sql);
        ?>

      <!-- Show Profile Start -->

    <div class="box">
       
         
            <div class="title">
            Inventory of Trucks
            </div>
        <div class="containers">
                <?php 
                    $select = mysqli_query($conn, "SELECT * FROM account WHERE id = '$user_id'")
                    or die('query Failed');
                    if(mysqli_num_rows($select) > 0) {
                    $fetch = mysqli_fetch_assoc($select);
                }
              $user = array();
                  if (isset($_GET['id'])) {
                  $id = $_GET['id'];
                
               
                  $db = mysqli_connect("localhost", "root", "", "transaction");
                  $sql = "SELECT * FROM truck WHERE id= '?'";
                  $stmt = $db->prepare($sql);
                  $stmt->bind_param('s', $id );
                  $stmt->execute();
                
                  $result = $stmt->get_result();  
                  $user = $result->fetch_assoc();

              }
              ?>

                <?php 
               
                $sql = "SELECT * FROM truck WHERE Truck_Status= 'Available'";
                $trucks = $conn->query($sql);  
                    if(mysqli_num_rows($trucks) > 0) {
                        while($row = mysqli_fetch_assoc($trucks)) {
                    ?>
                            <form action="PHP_file/StaffTruckVehicle_Selection.php" method="POST">

                            <input type="hidden" name="Staff_id" value="<?php echo $fetch['id'] ?>">
                                <input type="hidden" name="Staff_name" value="<?php echo $fetch['Firstname'] . ' ' . $fetch['Lastname']; ?>">
                                <input type="hidden" name="On_Use" value="Confirm">
                                <input type="hidden" name="Truck_Status" value="On-Use">
                                <input type="hidden" name="End_Use" value="Pending">
                                <div class="card">
                                <div class="profile">
                                        <?php
                                     
                                        $imageFolderPath = 'profile_image/';
                                                
                                       
                                        if (empty($row["Truck_Image"])) {
                                            $defaultImage = 'logo/OTS Logo.png'; 
                                        ?>
                                            <img src="<?php echo $defaultImage; ?>" alt="Default Image">
                                        <?php
                                        } else {
                                        ?>
                                            <img src="<?php echo $imageFolderPath . $row["Truck_Image"]; ?>" alt="Truck Image">
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="profile-details">
                                        <input type="hidden" name="Truck_Id" value="<?php echo $row["Truck_Id"]; ?>">
                                        <input type="hidden" name="Truck_PlateNum" value="<?php echo $row["Truck_PlateNumber"]; ?>">
                                        <p class="ProfileNumber">Truck Status: <?php echo $row["Truck_Status"]; ?></p>
                                        <p class="profileName">Truck Plate: <?php echo $row["Truck_PlateNumber"]; ?></p>
                                        <p class="ProfileEmail">Truck Type: <?php echo $row["Truck_Type"]; ?></p>
                                        <p class="Select"><button type="submit" name="submit_btn">Select Truck</button></p>
                                    </div>
                                </div>
                            </form>
                    <?php
                        }
                    } else {
                        echo "No trucks found.";
                    }?>
        </div>
    
</div>
<!-- Show Profile End -->



</body>
</html>
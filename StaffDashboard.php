<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "transaction");
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location: index.php');
    exit; 
}

$select = mysqli_query($conn, "SELECT * FROM account WHERE id = '$user_id'")
    or die('query Failed');

if(mysqli_num_rows($select) > 0) {
$fetch = mysqli_fetch_assoc($select);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="StaffDashboard.css">
    

    <title>Client Dashboard</title>
</head>

<body>

<!-- Top Nav Start -->
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
        <!-- End of Top Nav -->

<div class="container">
    <div class="record1">
        <form action="PHP_file/StaffDashboard_Truck.php" method="post">
        <?php 
             // Database connection
             $conn = mysqli_connect("localhost", "root", "", "transaction");
             if ($conn->connect_error) {
                 die('Connection failed: ' . $conn->connect_error);
             }

            $sql = "SELECT * FROM truck_record WHERE User_Id = '$user_id' && End_Use = 'Pending'";
            $result = $conn->query($sql);

                if ($result->num_rows > 0) { 
                    // $row1 = $result->fetch_assoc();
                    $row = $result->fetch_assoc();
                ?>

                    <input type="hidden" name="Truck_Num" value="<?php echo $row["id"]; ?>">
                    <input type="hidden" name="Truck_id" value="<?php echo $row["truck_Id"]; ?>">

            <h1 class="Greetings">Your Truck is <?php echo $row["Truck_Num"];?> </h1>
              <div class="logouttruck">
                    <input type="hidden" name="End_User" value="Confirm">
                    <input type="hidden" name="Truck_Status" value="Available">
                <p>If you are done availing the service <br> of this vehicle, you may click</p> 
                <input type="submit" name="logout_truck" value="Sign Out  ">
                </div>


            <div class="new_option1">
                
                <div class="Option1" id="imgInventory">
                    <!-- onClick="location.href='ClientFillUpPackage.php'" -->
                    <input type="button" value="Package Inventory" onClick="location.href='StaffInventoryPackage.php'">
                </div>
                <div class="Option1" id="imgtransaction">
                    <!-- onClick="location.href='ClientFillUpPackage.php'" -->
                    <input type="button" value="Transaction Record" onClick="location.href='StaffPackageTransactionStatus.php'">
                </div>

                <script>
                    // Function to set background image for an element by ID with fade-in effect
                    function setBackgroundImageWithFadeIn(elementId, imageUrl) {
                        var element = document.getElementById(elementId);
                        if (element) {
                            // Set background image
                            element.style.backgroundImage = "url('" + imageUrl + "')";
                            
                            // Add the fade-in class after a delay
                            setTimeout(function () {
                                element.classList.add('fade-in');
                            }, 0); // Adjust the delay (in milliseconds) according to your needs
                        }
                    }

                    // Call the function with the desired IDs and image URLs
                    setBackgroundImageWithFadeIn('imgInventory', 'logo/inventorypackage.png');
                    setBackgroundImageWithFadeIn('imgtransaction', 'logo/deliverytruck.png');
                </script>

            </div>

               <?php } else{ ?>
            </div> 
        </form>
    </div>

    <div class="record2">

      <!-- Show Profile Start -->

    <div class="box">
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
         
            <div class="title">
            Inventory of Trucks
            </div>
        
            <div class="greetingsvi">
                <span class="titlegreet">Hello Good Day <?php echo $fetch['Firstname']. ' ' . $fetch['Lastname']; ?>!</span>
                <br><span class="descgreet">(Please Choose Your Vehicle)</span>
            </div>





        <div class="containers">
         
                <?php 
               
                $sql1 = "SELECT * FROM truck WHERE Truck_Status= 'Available'";
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
                                     
                                        $imageFolderPath = 'Profile_Image/';
                                                
                                       
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
<?php } ?>
</div>
</body>
</html>
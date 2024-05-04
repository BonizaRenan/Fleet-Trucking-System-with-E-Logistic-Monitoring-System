<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header('location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];


$Transaction_ID = $_GET['Transaction_ID'];

// echo "Error";  
?>      

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ClientTransaction.css">
    <title>Package Info</title>
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


        <div class="container">
        <div class="Back_btn">
                <input type="button" value="Back" onclick="window.location.href='ClientDashboard.php'">
            </div>
        <?php
                $conn = mysqli_connect("localhost", "root", "", "transaction");
                if ($conn->connect_error) {
                    die('Connection failed: ' . $conn->connect_error);
                }
                        $stmt = $conn->prepare("SELECT * FROM client_package WHERE id = ? ");
                        $stmt->bind_param("i", $Transaction_ID); 
                        $stmt->execute();
                        $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $stmt2 = $conn->prepare("SELECT * FROM package_transaction WHERE Package_ID = ?");
                        $stmt2->bind_param("i", $Transaction_ID);
                        $stmt2->execute();
                        $result3 = $stmt2->get_result();
                        $Transaction = $result3->fetch_assoc()
        ?>
            <div class="title">
                        Package Transaction 
                    </div>    
                    <div class="StaffDetails">
                   
                    <a href="#" class="logo">
                        
                    <?php 
                        $staffID = $Transaction['Staff_id'];
                        
                        $select = mysqli_query($conn, "SELECT * FROM account WHERE id = '$staffID'")
                        or die('query Failed');

                        if(mysqli_num_rows($select) > 0) {
                            $fetch = mysqli_fetch_assoc($select);
                            
                    
                            $baseImagePath = 'package_Image/';
                            $defaultImage = 'logo/OTS Logo.png'; 

                            $imageExistsInDB = array_key_exists("Profile_image", $fetch) && !empty($fetch["Profile_image"]);
                            $fileExistsOnServer = file_exists($baseImagePath . $fetch["Profile_image"]);

                        
                            if ($imageExistsInDB && $fileExistsOnServer) {
                                $ProfileImage = $baseImagePath . $fetch["Profile_image"];
                                echo "<img class='profileImage' src='{$ProfileImage}' alt='Profile Image'>";
                            } else {
                                echo "<img class='defaultImage' src='{$defaultImage}' alt='Default Image'>";
                            }
                            
                            // Display user name
                            echo "<span><p>" . $fetch['Firstname'] . " " . $fetch['Lastname'] . "</p>";  
                            echo "<p class='usertype'>Courier's Name</p></span>";
                            
                        }
                    ?>

                </a>
                <a href="#" class="logo">

                    <?php 
                        $truckID = $Transaction['Truck_PlateNumber'];
                        
                        $select = mysqli_query($conn, "SELECT * FROM truck WHERE Truck_PlateNumber = '$truckID'")
                        or die('query Failed');

                        if(mysqli_num_rows($select) > 0) {
                            $fetch = mysqli_fetch_assoc($select);
                            
                    
                            $baseImagePath = 'Profile_Image/';
                            $defaultImage = 'logo/OTS Logo.png'; 

                            $imageExistsInDB = array_key_exists("Truck_Image", $fetch) && !empty($fetch["Truck_Image"]);
                            $fileExistsOnServer = file_exists($baseImagePath . $fetch["Truck_Image"]);

                        
                            if ($imageExistsInDB && $fileExistsOnServer) {
                                $ProfileImage = $baseImagePath . $fetch["Truck_Image"];
                                echo "<img class='profileImage' src='{$ProfileImage}' alt='Profile Image'>";
                            } else {
                                echo "<img class='defaultImage' src='{$defaultImage}' alt='Default Image'>";
                            }
                            
                            // // Display user name
                            echo "<span><p> " . $Transaction["Truck_PlateNumber"] . " </p>";  
                            echo "<p class='usertype'>Truck Number</p></span>";
                            
                        }
                    ?>

                </a>
                <a href="#" class="logo">
                    <span class="estimated"><p><?php echo date("M. d, Y", strtotime($Transaction["Estimated_Date"])); ?></p> <p class='usertype'>Estimated Date:</p></span>
                    </a>




            
                    </div>


                    <div class="order-progress">
                        <div class="step" id="OnGoingImage" style="background: none;">
                            <div class="icon">
                                <img src="icons/On-Going.png" alt="Step 1">
                            </div>
                            <span>Ongoing</span>
                        </div>
                        <div class="arrow-OngoingToMasbate" >
                            <img src="icons/Transaction-Arrow.png" alt="Arrow">
                        </div>
                        <div class="step" id="ArriveImage">
                            <div class="icon">
                                <img src="icons/Arrive_Masbate.png" alt="Step 2">
                            </div>
                            <span >Arrived</span>
                        </div>
                        <div class="arrow-MasbateToReceive">
                            <img src="icons/Transaction-Arrow.png" alt="Arrow">
                        </div>
                        <div class="step"  id="DeliveredImage">
                            <div class="icon">
                                <img src="icons/Recieve_Package.png" alt="Step 3">
                            </div>
                            <span>Received</span>
                        </div>
                    </div>

                    <div class="transaction-details"> 

                    <?php if($Transaction["Delivered"] == 'Confirm'){ ?>
                            <script>
                                var arriveElements = document.querySelectorAll("#DeliveredImage");
                                arriveElements.forEach(function(element) {
                                    element.style.background = "#66FF99";
                                    element.style.borderRadius = "40px";
                                });
                            </script>
                            <div class="On-going">
                                <img src="icons/CheckList.png" alt="Completed">
                                <div class="info">
                                    <span><?php echo $Transaction["Delivered_DateTime"]?></span>
                                    <strong>Received Package</strong>
                                    <p>The package has been received! Please check the attached image for proof of reception</p>
                                    <div class="ImageTransaction">
                                        <?php
                                      
                                        $stmtImages = $conn->prepare("SELECT Proof_Received FROM package_transaction WHERE Package_id = ?");
                                        $stmtImages->bind_param("s", $Transaction_ID);
                                        $stmtImages->execute();
                                        $resultImages = $stmtImages->get_result();

                                        $baseImagePath = 'ProofOfPackage/'; 

                                        while ($imageRow = $resultImages->fetch_assoc()) {
                                            $relativePath = trim($imageRow["Proof_Received"]);
                                            $packageImage = $baseImagePath . $relativePath;

                                            $absolutePath = __DIR__ . '/' . $packageImage;

                                            if (file_exists($absolutePath)) {
                                                echo "<div class='slide' style='background-image: url(\"{$packageImage}\");'></div>";
                                            } else {
                                                echo "Image not found: " . $absolutePath;
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            
                        <?php }?>  

                        <?php if($Transaction["Arrive_Masbate"] == 'Confirm'){ ?>
                            <script>
                                document.querySelector(".arrow-MasbateToReceive").style.visibility="visible";
                                var ongoingElements = document.querySelectorAll("#ArriveImage"); 
                                ongoingElements.forEach(function(element) {
                                    element.style.backgroundColor = "gray";
                                    element.style.borderRadius = "50px"; 
                                });
                            </script>

                            <div class="On-going">
                                <img src="icons/CheckList.png" alt="Completed">
                                <div class="info">
                                    <span><?php echo $Transaction["Arrive_Masbate_DateTime"]?></span>
                                    <strong>Arrived at Masbate</strong>
                                    <p>The Package has Arrived at Masbate</p>
                                </div>
                            </div>
                           
                        <?php }?>  
                        
                        <?php if($Transaction["Delay_Confirmation"] == 'Confirm'){ ?>
                            <script>
                                document.querySelector(".arrow-OngoingToMasbate").style.visibility="visible";

                                var ongoingElements = document.querySelectorAll("#OnGoingImage");
                                ongoingElements.forEach(function(element) {
                                    element.style.backgroundColor = "orange";
                                    element.style.borderRadius = "50px"; 
                                });
                            </script>
                            <div class="On-going">
                                <img src="icons/CheckList.png" alt="Completed">
                                <div class="info">
                                    <span><?php echo $Transaction["On_Going_DateTime"]?></span>
                                    <strong>Delayed Package</strong>
                                    <p>The Package has been Delayed</p>
                                </div>
                            </div>
                        <?php }?>


                        <?php if($Transaction["On_Going"] == 'Confirm'){ ?>
                            <script>
                                document.querySelector(".arrow-OngoingToMasbate").style.visibility="visible";

                                var ongoingElements = document.querySelectorAll("#OnGoingImage");
                                ongoingElements.forEach(function(element) {
                                    element.style.backgroundColor = "orange";
                                    element.style.borderRadius = "50px"; 
                                });
                            </script>
                            <div class="On-going">
                                <img src="icons/CheckList.png" alt="Completed">
                                <div class="info">
                                    <span><?php echo $Transaction["On_Going_DateTime"]?></span>
                                    <strong>Ongoing Package</strong>
                                    <p>The Package is ongoing to Masbate</p>
                                </div>
                            </div>
                        <?php }?>
                        <!-- ... More steps if any ... -->
                    </div>
                        
                </div>
        <?php }}?>       
        </div>
        
    
   
        
</body>
</html>
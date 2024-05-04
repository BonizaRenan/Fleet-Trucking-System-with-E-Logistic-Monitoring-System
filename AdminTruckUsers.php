<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "transaction");
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location: index.php');
    exit; 
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="AdminTruckUsers.css">
    <title>User Truck Record</title>
</head>
<body>
       <!-- nav start -->
       <nav>
        <ul>
        <li>
    <a href="#" class="logo">
        <?php 
            $select = mysqli_query($conn, "SELECT * FROM account WHERE id = '$user_id'")
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
                
            
                echo "<span><p>" . $fetch['Firstname'] . " " . $fetch['Lastname'] . "</p>";  
                echo "<p class='usertype'>Administrator</p></span>";
            }
        ?>
    </a>
</li>
        <div class="nav-align">
             <li>
                <a href="AdminDashboard.php" >
                    <img src="icons/home.png" alt="" class="icon">
                    <span  class="nav-item">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="AdminPackageReview.php">
                    <img src="icons/Reviewing.png" alt="" class="icon">
                    <span class="nav-item">Package Review</span>
                </a>
            </li>
            <li>
                <a href="AdminPaymentTransaction.php">
                    <img src="icons/transaction.png" alt="" class="icon">
                    <span class="nav-item">Payment Transaction</span>
                </a>
            </li>

             <li>
                <a href="AdminInventoryPackage.php">
                    <img src="icons/package.png" alt=""class="icon">
                    <span  class="nav-item">Package Inventory</span>
                </a>
            </li>

            <li>
                <a href="AdminTransactionRecord.php">
                    <img src="icons/transactionRecord.png" alt=""class="icon">
                    <span  class="nav-item">Transaction Record</span>
                </a>
            </li>


             <li>
                <a href="AdminTruckInventory.php">
                    <img src="icons/truck.png" alt="" class="icon">
                    <span class="nav-item">Transport Inventory</span>
                </a>
            </li>

             <li>
                <a href="AdminStaffAccount.php">
                    <img src="icons/user.png" alt="" class="icon">
                    <span  class="nav-item">Staff Account</span>
                </a>
            </li>

            </div>
        </ul>
    </nav>
        <div class="TopNav">
                <div class="nav-icon">
                    <img src="icons/icons8-menu-50.png" alt="" class="navbtn ">
                    <script>
                       const navBtn = document.querySelector(".navbtn");
                        const navMenu = document.querySelector("nav");
                                        
                        navBtn.addEventListener("click", function() {
                     
                            navMenu.classList.toggle('open');
                        });
                        
                        document.addEventListener("click", function(event) {
                            if (!navMenu.contains(event.target) && !navBtn.contains(event.target)) {
                            
                                navMenu.classList.remove('open');
                            }
                        });
                    </script>
                </div>
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
        
        <script>
            document.querySelector(".Search_btn").addEventListener("click", function() {
            const searchQuery = document.getElementById('search_input').value;
            window.location.href = window.location.pathname + '?search=' + encodeURIComponent(searchQuery);
            });
        </script>

        <?php
        
            $conn = mysqli_connect("localhost", "root", "", "transaction");
            // $sql = "SELECT * FROM client_package WHERE Payment_Confirmation = 'Pending'";
            $sql = "SELECT * FROM truck_record ORDER BY id DESC";


                if (isset($_GET['search'])) {
                    $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);
                    $sql .= " AND (Staff_name LIKE '%$searchTerm%' OR Truck_Num LIKE '%$searchTerm%')";
                }

            $Search = $conn->query($sql);
        ?>

         <!-- Search bar End -->
         <div class="box">

<div class="truckBackBTN">
        <input type="button" value="Back" onclick="window.location.href='AdminTruckInventory.php'">
</div>

<div class="title">
    Truck User Record
    </div>
<div class="table-container">
    <table class="Table">
        <tr>
            <th>No.</th>
            <th>Staff Name</th>
            <th>Truck Number</th>
            <th>In Use</th>
            <th>Date and Time</th>
            <th>End Use</th>
            <th>Date and Time</th>
            <!-- <th></th>
            <th></th> -->
        </tr>
    <?php
    // Database connection
    $conn = mysqli_connect("localhost", "root", "", "transaction");
        
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }


    $result = $conn->query($sql);


    if ($result->num_rows > 0) {
        $counter=1;
        while ($row = $Search->fetch_assoc())  {
            

            $transaction_Id = $row["id"];
            //$uniqueID = "popup_" . $row['id'];
            //$buttonID = "accept_" . $row['id'];
            // $client_id = $row["Client_ID"];
            // $sqlDetails3 = "SELECT * FROM account WHERE id = ?";
            // $stmt3 = $conn->prepare($sqlDetails3);
            // $stmt3->bind_param('i', $client_id);
            // $stmt3->execute();
            // $detailsResult3 = $stmt3->get_result();
            // $Profile = $detailsResult3->fetch_assoc();
    ?>
<div id="onlinePayment" style="display: none;">
              <h3>Online Payment</h3>
        
        <tr >
            <td><?php echo $counter++; ?></td>
            <td><?php echo $row["Staff_name"]; ?></td>
            <td><?php echo $row["Truck_Num"]; ?></td>
            <td><?php echo strtoupper($row["On_Use"]); ?></td>
            <td><?php echo $row["In_Date_Time"]; ?></td>
            <td><?php echo $row["End_Use"]; ?></td>


            <td><?php echo $row["Out_Date_Time"]; ?></td>



        </tr>

</div>
        <?php

    }
    } else {
        // echo "No Record.";
    }
    ?>
    </table>
</div>
</div>
</body>
</html>
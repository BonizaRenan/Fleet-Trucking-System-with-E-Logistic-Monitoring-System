<?php
session_start();
    include("PHP_file/AdminTruckInventory_Create.php");
    $user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location: index.php');
    exit; 
}
$successMessage = ''; // Initialize the success message variable

if (isset($_GET['success_message'])) {
    $successMessage = htmlspecialchars($_GET['success_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="AdminTruckInventory.css">
    <title>Truck Inventory</title>
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
         <!-- Search bar End -->
        <script>
        document.querySelector(".Search_btn").addEventListener("click", function() {
        const searchQuery = document.getElementById('search_input').value;
        window.location.href = window.location.pathname + '?search=' + encodeURIComponent(searchQuery);
        });
        </script>
        <?php
       
         $sql = "SELECT * FROM truck";

      
         if (isset($_GET['search'])) {
          
             $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);

           
             $sql .= " WHERE (Truck_PlateNumber LIKE '%$searchTerm%' OR Truck_Type LIKE '%$searchTerm%' OR Truck_Status LIKE '%$searchTerm%')";
         }

    $trucks = $conn->query($sql);

    
        ?>

        <!-- Add Button start -->
        <div class="Add_Truck">
            <input type="button" class="Add-account "value="Add Vehicle">
        </div>
             <!-- Add Button end -->
                    <!-- Add Truck PopUp Start -->
                    <div class="Add_Popup">

<div class="add_form"> 
    <form action="PHP_file/AdminTruckInventory_Create.php" method="post" autocomplete="off" enctype="multipart/form-data">

    <span class="Iconclose">
    <img src="icons/icons8-go-back-50.png" class="Icon-close"alt="">
</span>


    <div class="title-register">
            Vehicle Registration
            </div>
            
    <!-- Image Upload Section in Vehicle Registration Popup -->
            <div class="uploadimage_Truck">
                <div class="imagePreview_Truck">
                    <?php
                    echo "<img class='defaultImage' src='logo/OTS Logo.png' alt='Default Image'>";
                    ?>
                </div>
                <div class="file_btn">
                    <button id="fileButton" class="filebutton">Upload Image</button>
                    <input type="file" name="imageFiles5" id="imageFile" class="imageFile" accept="image/*" multiple style="display: none;">
                </div>
                <script>
                 document.addEventListener("DOMContentLoaded", function() {
                    const imageFileInput = document.getElementById('imageFile');
                    const fileButton = document.getElementById('fileButton');
                                
                    fileButton.addEventListener('click', function() {
                        imageFileInput.click();
                    });
                
                    imageFileInput.addEventListener('change', function(event) {
                        const previewDiv = document.querySelector('.imagePreview_Truck');
                        previewDiv.innerHTML = '';
                    
                        for (let i = 0; i < event.target.files.length; i++) {
                            const file = event.target.files[i];
                            
                            if (file) {
                                const img = document.createElement('img');
                                img.src = URL.createObjectURL(file);
                                img.onload = function() {
                                    URL.revokeObjectURL(img.src);
                                }
                            
                                previewDiv.appendChild(img);
                            }
                        }
                    
                    
                        if (event.target.files.length === 0) {
                            const defaultImg = document.createElement('img');
                            defaultImg.src = 'logo/OTS Logo.png';
                            defaultImg.className = 'defaultImage';
                            defaultImg.alt = 'Default Image';
                            previewDiv.appendChild(defaultImg);
                        }
                    });
                });
                </script>

            </div>

        <div class="user-details">
            <div class="input-box">
                <span class="details">Truck Plate Number</span>
                <input type="text" name="TruckPlateNum" id="TruckPlateNum" placeholder="Enter Truck Plate Number" required>
            </div>

                <div class="input-box">
                    <span class="details">Truck Type</span>
                    <!-- <input type="text" name="TruckType" id="TruckType" placeholder="Enter Truck Type" required> -->
                    <select name="TruckType" id="TruckType" required>   
                        <option value="14 Wheeler">14 Wheeler</option>
                        <option value="16 Wheeler">16 Wheeler</option>
                    </select>
                </div>

                <!-- <div class="input-box">
                    <span class="details">Truck Space</span>
                    <input type="text" name="TruckSpace" id="TruckSpace" placeholder="Enter Truck Space" required>
                </div> -->

                <div class="input-box">
                            <span class="details">Truck Status</span>
                                <select id="Truck_Status" name="TruckStatus">
                                  <option value="Available">Available</option>
                                  <option value="On-Going">On-Going</option>
                                  <option value="Maintenance">Maintenance</option>
                                  <option value="Coding">Coding</option>
                                </select>
                            <!-- <input type="text" name="TruckStatus" id="TruckStatus" value="<?php echo htmlspecialchars($user['Truck_Status']); ?>"  required> -->
                        </div>
            </div>
               
 
        <div class="button">
                <input type="submit" name="submit" id="submit" value="Register">
        </div>
    </div>
</form>
</div>
        <script>
        document.querySelector(".Add-account").addEventListener("click", function() {
        document.querySelector(".Add_Popup").style.display = "flex";
        });

        document.querySelector(".Icon-close").addEventListener("click", function() {
        document.querySelector(".Add_Popup").style.display = "none";
        });

        </script>
<!-- Add Truck PopUp End -->

      <!-- Show Profile Start -->

<div class="box">
<div class="TruckBTN">
            <input type="button" value="User Record" onclick="window.location.href='AdminTruckUsers.php'">
    </div>
    <div class="title">
        Inventory of Trucks
    </div>
    <div class="containers">
        <?php
          
          $user = array();
              if (isset($_GET['id'])) {
              $id = $_GET['id'];
          

              $db = mysqli_connect("localhost", "root", "", "transaction");
              $sql = "SELECT * FROM truck WHERE Truck_Id = ?";
              $stmt = $db->prepare($sql);
              $stmt->bind_param('i', $id);
              $stmt->execute();
          
              $result = $stmt->get_result();  
              $user = $result->fetch_assoc();

          }
          
              if(mysqli_num_rows($trucks) > 0) {
              while($row = mysqli_fetch_assoc($trucks)){

        ?>
                <div class="card">
                <div class="option">
                        <div class="Update">
                                <a href="?id=<?php echo $row['Truck_Id']; ?>&action=update" class="update_btn">
                                    <img src="icons/edit.png" alt="">
                                </a>
                        </div>
                        <div class="Delete">
                            <a href="?id=<?php echo $row['Truck_Id']; ?>&action=delete">
                                <img src="icons/delete.png" alt="">
                             </a>
                        </div>
                    </div>

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
                    <p class="ProfileNumber">Truck Status: <?php echo $row["Truck_Status"]; ?></p>
                        <p class="profileName">Truck Plate: <?php echo $row["Truck_PlateNumber"]; ?></p>
                        <p class="ProfileEmail">Truck Type: <?php echo $row["Truck_Type"]; ?></p>
                        <!-- <p class="ProfileNumber">Truck Space: <?php //echo $row["Truck_Space"]; ?></p> -->
                    </div>
                </div>
        <?php
            }
        } else {
            echo "No trucks found.";
        }
        ?>
    </div>
</div>
<!-- Show Profile End -->

<!-- Update Start -->
<?php if (!empty($user) && isset($_GET['action']) && $_GET['action'] == 'update'): ?>
    <div class="update_Popup">
            <div class="Update_case">
                <form action="PHP_file/AdminTruckInventory_Update.php" method="post" autocomplete="off"  enctype="multipart/form-data">
                    <input type="hidden" id="hiddenId" name="id" value="<?php echo htmlspecialchars($user['Truck_Id']); ?>">

                        <span class="Iconclose">
                            <img src="icons/icons8-go-back-50.png" class="Icon_close" alt="">
                        </span>
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                            document.querySelectorAll(".Icon_close").forEach(function(button) {
                            button.addEventListener("click", function(event) {
                            event.preventDefault();
                            document.querySelector(".update_Popup").style.display = "none";

                        
                            var url = window.location.toString();
                            if (url.indexOf("?") > 0) {
                                var clean_url = url.substring(0, url.indexOf("?"));
                                window.history.replaceState({}, document.title, clean_url);
                            }
                        });
                    });
                    });
                    </script>

                        <div class="title_update">
                            Update Vehicle
                        </div>
                        
                        <div class="uploadimage_Truck2">
                            <div class="imagePreview_Truck" id="imagePreview2">
                                <?php
                                $baseImagePath = 'profile_image/';
                                $defaultImage = 'logo/OTS Logo.png';

                              
                                echo "<img class='defaultImage' src='{$defaultImage}' alt='Default Image'>";

                               
                                if ($user && isset($user["Truck_Image"]) && file_exists($baseImagePath . $user["Truck_Image"])) {
                                    $ProfileImage = $baseImagePath . $user["Truck_Image"];
                                    echo "<img class='profileImage' src='{$ProfileImage}' alt=''>";
                                }
                                ?>
                            </div>
                            <input type="file" name="imageFiles" id="imageFile2" accept="image/*">
                        </div>

            <script>
                  const imageFileInput = document.getElementById('imageFile2');
                    const imagePreview = document.querySelector('#imagePreview2');

                    imageFileInput.addEventListener('change', function() {
                        const file = this.files[0];  
                    
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const img = document.createElement('img');
                                img.src = e.target.result;
                                imagePreview.innerHTML = ''; 
                                imagePreview.appendChild(img); 
                            }
                            reader.readAsDataURL(file);
                        }
                    });
            </script>
                        <div class="user-details">
                        <div class="input-box">
                            <span class="details">Truck Plate Number</span>
                            <input type="text" name="TruckPlateNum" id="TruckPlateNum" value="<?php echo htmlspecialchars($user['Truck_PlateNumber']); ?>" required>
                        </div>

                        <!-- <div class="input-box">
                            <span class="details">Truck Type</span>
                            <input type="text" name="TruckType" id="TruckType" value="<?php //echo htmlspecialchars($user['Truck_Type']); ?>" required>
                        </div> -->

                        <div class="input-box">
                            <span class="details">Truck Type</span>
                            <select name="TruckType" id="TruckType" required>
                                <option value="14 Wheeler" <?php if ($user['Truck_Type'] == '14 Wheeler') echo 'selected'; ?>>14 Wheeler</option>
                                <option value="16 Wheeler" <?php if ($user['Truck_Type'] == '16 Wheeler') echo 'selected'; ?>>16 Wheeler</option>
                                <!-- Add more options as needed -->
                            </select>
                        </div>

                        <!-- <div class="input-box">
                            <span class="details">Truck Space</span>
                            <input type="text" name="TruckSpace" id="TruckSpace" value="<?php //echo htmlspecialchars($user['Truck_Space']); ?>"  required>
                        </div> -->

                        <div class="input-box">
                            <span class="details">Truck Status</span>
                                <select id="Truck_Status" name="Truck_Status">
                                  <option value="Available">Available</option>
                                  <option value="On-Going">On-Going</option>
                                  <option value="Maintenance">Maintenance</option>
                                  <option value="Coding">Coding</option>
                                </select>
                            <!-- <input type="text" name="TruckStatus" id="TruckStatus" value="<?php echo htmlspecialchars($user['Truck_Status']); ?>"  required> -->
                        </div>

                    </div>
                    <!-- <h4 class="Alert">Register Succesfully</h4> -->
                <div class="button">
                        <input type="submit" name="update" id="submit" value="Update">

                </form>
            </div>
    </div>
    <?php endif; ?>
<!-- Delete Start -->
<?php if (!empty($user) && isset($_GET['action']) && $_GET['action'] == 'delete'): ?>
    <div class="Delete_Popup">
        <div class="Delete_case"> 
            <form action="PHP_file/AdminTruckInventory_Delete.php" method="post">
            <input type="hidden" name="id" value="<?php echo $user['Truck_Id']; ?>">
        <div class="title_Delete">
            Delete Confirmation
        </div>
        <div class="descript_delete">
            Are you sure want to delete this Vehicle of <b><?php echo $user["Truck_PlateNumber"]; ?></b>?<br>
        </div>
        <div class="delete_btn">
            <button type="button" class="close" onclick="document.querySelector('.Delete_Popup').style.display = 'none'">Close</button>
            <button type="submit"class="Delete">Delete</button>
        </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php //if(isset($_SESSION['deleted'])): ?>
    <script type="text/javascript">
        // alert("<?php // echo $_SESSION['message']; ?>");
    </script>
    <?php 
        // unset($_SESSION['deleted']); 
        // unset($_SESSION['message']); 
   // endif; 
    ?>
<!-- Delete End -->

<div id="success-popup" class="popupsuccess">
    <div class="popup-content">
        <span class="close" id="close-success-popup">&times;</span>
        <img src="icons/Check.png" alt="Checkmark" class="checkmark-icon">
        <div class="popup-message" id="success-popup-message"></div>
    </div>
</div>
<script>
   
    function showSuccessPopup() {
        const successPopup = document.getElementById('success-popup');
        const successPopupMessage = document.getElementById('success-popup-message');

        successPopupMessage.innerHTML = '<?php echo $successMessage; ?>';
        successPopup.style.display = 'block';

        
         setTimeout(function() {
            successPopup.classList.add('fade-out'); 
         
            successPopup.addEventListener('animationend', function() {
                successPopup.style.display = 'none';
            });
        }, 1000); 
    }

 
    if ('<?php echo $successMessage; ?>' !== '') {
        showSuccessPopup();
    }
</script>
</body>
</html>
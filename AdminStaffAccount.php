<?php
    require 'PHP_file/AdminStaffAccount_Create.php';

    $successMessage = ''; 

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
    <link rel="stylesheet" href="css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="AdminStaffAccount.css">
    <title>Staff Account</title>
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
            
       <!-- Add Button start -->
       <div class="addacc">
            <input type="button" class="Add-account "value="Add Account">
        </div>
            <!-- Add Button end -->

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
       
         $sql = "SELECT * FROM account WHERE user_type= 'staff'";

        
         if (isset($_GET['search'])) {
          
             $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);

         
             $sql .= " AND (Firstname LIKE '%$searchTerm%' OR Lastname LIKE '%$searchTerm%' OR Email LIKE '%$searchTerm%')";
         }

    $staff = $conn->query($sql);
        ?>
            <!-- Search bar end -->

        <!-- Pop-Up  Registration start -->
        <div class="Add_Popup">
            <div class="add_form"> 
                <form action="PHP_file/AdminStaffAccount_Create.php" method="post" autocomplete="off" onsubmit="return validateForm()">

            <!-- This is for close tag -->
            <?php
            
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
              
                $currentScriptName = basename($_SERVER['PHP_SELF']);
             
                if ($currentScriptName == 'AdminStaffAccount_Create.php' && isset($_GET['EndRegister'])) {
              
                    session_destroy();
                
                
                    header('Location: PHP_file/AdminStaffAccount_Create.php');
                    exit();
                }
                ?>
                <span class="Iconclose">
                    <a href="?EndRegister=true">
                        <img src="icons/icons8-go-back-50.png" class="Icon-close" alt="">
                    </a>
                </span>
            <!-- This is for close tag end -->

            <!-- Registration form start -->
                <div class="title-register">
                        Staff Registration
                        </div>
                   
                    <div class="user-details">
                        <div class="input-box">
                            <span class="details">Firstname</span>
                            <input type="text" name="Firstname" id="Firstname" placeholder="Enter your Firstname" required>
                        </div>

                        <div class="input-box">
                            <span class="details">Lastname</span>
                            <input type="text" name="Lastname" id="Lastname" placeholder="Enter your Lastname" required>
                        </div>

                        <div class="input-box">
                            <span class="details">Username</span>
                            <input type="text" name="Username" id="Username" placeholder="Enter your Username" required>
                        </div>

                        <div class="input-box">
                            <span class="details">Email</span>
                            <input type="text" name="Email" id="Email" placeholder="Enter your Email" required>
                        </div>
                    <script>
                      const emailInput = document.getElementById('Email');

                        emailInput.addEventListener('input', function() {
                        const email = emailInput.value;
                        if (email.includes('@') && email.includes('.com')) {
                          emailInput.setCustomValidity('');
                        } else {
                          emailInput.setCustomValidity('Please enter a valid email address.');
                        }
                      });
                    </script>
                        <div class="input-box">
                            <span class="details">Phone Number</span>
                            
                            <input type="text" name="PhoneNum" id="PhoneNum" placeholder="Enter your Phone Number" required>
                            <script>
                                    const mobileInput = document.getElementById('PhoneNum');

                                    mobileInput.addEventListener('input', function() {
                                        let mobileValue = mobileInput.value;
                                    
                                    
                                        mobileValue = "+63" + mobileValue.replace(/[^0-9]/g, '').substring(2);
                                    
                                      
                                        if (mobileValue.length > 13) {
                                            mobileValue = mobileValue.substring(0, 13);
                                        }
                                    
                                        mobileInput.value = mobileValue;
                                    });
                                
                                    mobileInput.addEventListener('focus', function() {
                                        if (mobileInput.value === "") {
                                            mobileInput.value = "+63";
                                        }
                                    });
                            </script>
                        </div>

                        <div class="input-box">
                            <span class="details">Password</span>
                            <!-- <input type="password" name="Password" id="Password" placeholder="Enter your Password" required> -->
                            <div class="password_merge">
                                <input type="password" name="Password" id="Password" placeholder="Enter Your New Password">
                                <img src="logo/icons8-hideblack-50.png" alt="" class="icon2" id="hide" onclick="pass1()">
                            </div>

                            <script>
                            var a
                            function pass1(){
                                if(a==1){
                                    document.getElementById('Password').type='password';
                                    document.getElementById('hide').src='logo/icons8-hideblack-50.png';
                                    a=0;
                                }
                                else{
                                    document.getElementById('Password').type='text';
                                    document.getElementById('hide').src='logo/icons8-eyeblack-50.png';
                                    a=1;
                                }
                            }
                        </script>

                        </div> 

                        <div class="input-box">
                            <span class="details">Confirm Password</span>
                           
                            <div class="confirmpassword_merge">
                                <input type="password" name="ConfirmPassword" id="ConfirmPassword" placeholder="Enter Confirm Password" required>
                                <img src="logo/icons8-hideblack-50.png" alt="" class="icon3" id="hide2" onclick="pass2()">
                            </div>
                            <script>
                        var a
                        function pass2(){
                            if(a==1){
                                document.getElementById('ConfirmPassword').type='password';
                                document.getElementById('hide2').src='logo/icons8-hideblack-50.png';
                                a=0;
                            }
                            else{
                                document.getElementById('ConfirmPassword').type='text';
                                document.getElementById('hide2').src='logo/icons8-eyeblack-50.png';
                                a=1;
                            }
                        }
                    </script>
                        </div> 
                    <script>
                    function validateForm() {
                        var password = document.getElementById('Password').value;
                        var confirmPassword = document.getElementById('ConfirmPassword').value;

                    if (password !== confirmPassword) {
                        document.getElementById('ConfirmPassword').setCustomValidity("Passwords do not match.");
                        return false;
                    } else {
                        document.getElementById('ConfirmPassword').setCustomValidity('');
                        return true;
                    }
                }
                </script>
                        <div class="user-type">
                        <input type="hidden" id="Usertype" name="Usertype" value="staff">
                        <input type="hidden" id="Usertype" name="ActiveStatus" value="Offline">
                        </div> 

                    </div>

                    <div class="gender-details">
                        <input type="radio" name="gender" id="dot-1" value="Male">
                        <input type="radio" name="gender" id="dot-2" value="Female">
    
                        <span class="gender-title">Gender</span>
                        <div class="category">

                            <label for="dot-1">
                                <span class="dot one"></span>
                                <span class="gender">Male</span>
                            </label>

                            <label for="dot-2">
                                <span class="dot two"></span>
                                <span class="gender">Female</span>
                            </label>
                        </div>
                    </div>

                    <div class="button">
                        <input type="submit" name="submit" id="submit" value="Register">
                    </div>

            <?php
                if (isset($_GET['addMsg'])) {
                    $message = htmlspecialchars($_GET['addMsg']);

                if ($message == 'Username or Email Has Already Taken') {
                    $class = 'error-msg';
                } 
                elseif ($message == 'Registered Successfully') {
                $class = 'success-msg';
                }
            ?>
            <p class="addMsg <?php echo $class; ?>"><?php echo $message; ?></p>
        <?php } ?> 

                </div>
            </form>
        </div>
        <!-- Registration form end -->
        <script>
            window.addEventListener("load", function() {
            var alertElement = document.querySelector(".addMsg");
            alertElement.style.opacity = "0";
            alertElement.style.visibility = "none";
                
           
            setTimeout(function() {
                alertElement.style.opacity = "1";
            }, 100);
                
           
            setTimeout(function() {
            alertElement.style.transition = "opacity 1s";
            alertElement.style.opacity = "0";
            setTimeout(function() {
            alertElement.style.display = "hidden";
            }, 1000);
            }, 2000);
            });

            document.querySelector(".Add-account").addEventListener("click", function() {
            document.querySelector(".Add_Popup").style.display = "flex";
            });

            document.querySelector(".Icon-close").addEventListener("click", function() {
            document.querySelector(".Add_Popup").style.display = "none";
            });

            window.addEventListener("beforeunload", function() {
            document.querySelector(".Add_Popup").style.display = "none";
            });

            window.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const addMsg = urlParams.get('addMsg');

            if (addMsg) {
            document.querySelector(".Add_Popup").style.display = "flex";
            }
            });

        </script>
        
        <!-- Pop-Up Registration End -->

        <!-- Show Profile Start -->
    
    <div class="box">
            <div class="title">
                Account of Staff
            </div>
            <div class="containers">
                <?php
                $user = array();
                    if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                
                   
                    $db = mysqli_connect("localhost", "root", "", "transaction");
                    $sql = "SELECT * FROM account WHERE id = ?";
                    $stmt = $db->prepare($sql);
                    $stmt->bind_param('i', $id);
                    $stmt->execute();
                
                    $result = $stmt->get_result();  
                    $user = $result->fetch_assoc();

                }
                  
                    $sql = "SELECT * FROM account WHERE user_type= 'staff'";
                if (isset($_GET['search'])) {
                    
                    $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);
        
                   
                    $sql .= " AND (Firstname LIKE '%$searchTerm%' OR Lastname LIKE '%$searchTerm%' OR Email LIKE '%$searchTerm%')";
                }

                    $staff = $conn->query($sql);
                    if(mysqli_num_rows($staff) > 0) {
                    while($row = mysqli_fetch_assoc($staff)){

                ?>
            <div class="card">
                <div class="option">

                    <div class="Update">
                            <a href="?id=<?php echo $row['id']; ?>&action=update" class="update_btn">
                                <img src="icons/edit.png" alt="">
                            </a>
                    </div>

                    <div class="Delete">
                        <a href="?id=<?php echo $row['id']; ?>&action=delete">
                            <img src="icons/delete.png" alt="">
                        </a>
                    </div>
                </div>

                    <div class="uploadimage">
                        <div class="imagePreview">
                    <?php
                        $baseImagePath = 'Profile_Image/';
                        $defaultImage = 'logo/OTS Logo.png'; 

                     
                        echo "<img class='defaultImage' src='{$defaultImage}' alt='Default Image'>";

                       
                        if ($row && isset($row["Profile_image"]) && file_exists($baseImagePath . $row["Profile_image"])) {
                            $ProfileImage = $baseImagePath . $row["Profile_image"];
                            echo "<img class='profileImage' src='{$ProfileImage}' alt=''>";
                        }
                    ?>
                        </div>
                    </div>
                
                <div class="profile-details">
                    <p class="hidden" name="id"><?php echo $row["id"] ?></p>
                    <p class="ActiveStatus">
                        <b>Status:</b>  
                    <?php if(strtolower($row["Active_Status"]) == 'active'): ?>
                            <img src="icons/GreenActive.png" alt="Active">
                    <?php else: ?>
                            <img src="icons/RedOffline.png" alt="Offline">
                    <?php endif; ?>
                            <?php echo $row["Active_Status"]?>
                    </p>
                    
                    <p class="profileName"><b>Name:</b><br> <?php echo $row["Firstname"]?> <?php echo $row["Lastname"]?></p>
                    <p class="ProfileEmail"><b>Email:</b> <br> <?php echo $row["Email"]?></p>
                    <p class="ProfileNumber"><b>CP No. :</b> <br><?php echo $row["Phone_Num"]?></p>
                </div>
            </div>
            <?php
                }
            } else {
                echo "No results found";
            }
        ?>
        </div>
    </div>       
    <!-- Show Profile End -->


    <!-- Update Start -->
    <?php if (!empty($user) && isset($_GET['action']) && $_GET['action'] == 'update'): ?>
        <div class="update_Popup">
            <div class="Update_case"> 


            <form action="PHP_file/AdminStaffAccount_Update.php" method="post" autocomplete="off" >
                <input type="hidden" id="hiddenId" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
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
                Update Account
            </div>
            <div class="user-details">
                <div class="input-box">
                    <span class="details">Firstname</span>
                    <input type="text" name="Firstname" id="Firstname"  value="<?php echo htmlspecialchars($user['Firstname']); ?>" required>
                </div>

                <div class="input-box">
                    <span class="details">Lastname</span>
                    <input type="text" name="Lastname" id="Lastname"  value="<?php echo htmlspecialchars($user['Lastname']); ?>" required>
                </div>

                <div class="input-box">
                    <span class="details">Username</span>
                    <input type="text" name="Username" id="Username" value="<?php echo htmlspecialchars($user['Username']); ?>" required>
                </div>

                <div class="input-box">
                    <span class="details">Email</span>
                    <input type="text" name="Email" id="Email2" value="<?php echo htmlspecialchars($user['Email']); ?>" required>
                </div>
                <div class="input-box">
                    <span class="details">Phone Number</span>
                    <input type="text" name="PhoneNum" id="PhoneNum" value="<?php echo htmlspecialchars($user['Phone_Num']); ?>" required>
                </div>

            </div>
                <div class="button">
                    <input type="submit" name="Update" id="submit" value="Update">
                </div>
                <h4 class="MSG"></h4>
            
        </form>

    </div>
    
    <script>
         window.addEventListener("load", function() {
        if ("<?php echo isset($_SESSION['message']); ?>") {
            var messageElement = document.querySelector(".MSG");
            document.querySelector(".update_Popup").style.display = "flex";
            messageElement.textContent = "<?php echo $_SESSION['message']; ?>";
            
          
            var msgType = "<?php echo $_SESSION['msg_type']; ?>";
            messageElement.classList.add(msgType === 'danger' ? 'msg-danger' : 'msg-success');

            messageElement.style.opacity = "0";
            messageElement.style.display = "flex";
            
          
            setTimeout(function() {
                messageElement.style.opacity = "1";
            }, 100);
            
         
            setTimeout(function() {
                messageElement.style.transition = "opacity 1s";
                messageElement.style.opacity = "0";
                setTimeout(function() {
                    messageElement.style.display = "none";
                }, 1000);
            }, 2000);

            <?php unset($_SESSION['message']); ?>
        }
    });
 
   
    const emailInput2 = document.getElementById('Email2');

    emailInput2.addEventListener('input', function() {
        console.log('Input event triggered');
        const email = emailInput2.value;
        if (email.includes('@') && email.includes('.com')) {
            emailInput2.setCustomValidity('');
        } else {
            emailInput2.setCustomValidity('Please enter a valid email address.');
        }
    });

</script>
    <?php endif; ?>
        </div>

    <!-- Update End -->

    <!-- Delete Start -->
    <?php if (!empty($user) && isset($_GET['action']) && $_GET['action'] == 'delete'): ?>
    <div class="Delete_Popup">
        <div class="Delete_case"> 
            <form action="PHP_file/AdminStaffAccount_Delete.php" method="post">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
        <div class="title_Delete">
            Delete Confirmation
        </div>
        <div class="descript_delete">
            Are you sure want to delete this Account of <b><?php echo $user["Firstname"]; ?> <?php echo $user["Lastname"]; ?></b>?<br>
        </div>
        <div class="delete_btn">
            <button type="button" class="close" onclick="document.querySelector('.Delete_Popup').style.display = 'none'">Close</button>
            <button type="submit"class="Delete">Delete</button>
        </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php if(isset($_SESSION['deleted'])): ?>
    <script type="text/javascript">
        alert("<?php echo $_SESSION['message']; ?>");
    </script>
    <?php 
        unset($_SESSION['deleted']); 
        unset($_SESSION['message']); 
    endif; 
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
<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "transaction");
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location: index.php');
    exit; 
}

$successMessage = ''; 

if (isset($_GET['success_message'])) {
    $successMessage = htmlspecialchars($_GET['success_message']);
}

$sqlDetails3 = "SELECT * FROM account WHERE id = ?";
$stmt3 = $conn->prepare($sqlDetails3);
$stmt3->bind_param('i', $user_id);
$stmt3->execute();
$detailsResult3 = $stmt3->get_result();
$Profile = $detailsResult3->fetch_assoc();




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profile.css">
    <title>Profile</title>
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
        <div class="backbtn">
            <?php
                $userRole = $Profile['user_type']; 

                $backUrl = '';

                switch($userRole) {
                    case 'client':
                        $backUrl = 'ClientDashboard.php';
                        break;
                    case 'admin':
                        $backUrl = 'AdminDashboard.php';
                        break;
                    case 'staff':
                        $backUrl = 'StaffDashboard.php';
                        break;
                }
            ?>

            <input type="button" value="Back" onClick="location.href='<?php echo $backUrl; ?>'">
            
           

        </div>

        <div class="title">
            Profile
        </div>

        <div class="uploadimage">
                <div class="imagePreview">
                    <?php
                        $baseImagePath = 'Profile_Image/';
                        $defaultImage = 'logo/OTS Logo.png'; 

                       
                        echo "<img class='defaultImage' src='{$defaultImage}' alt='Default Image'>";

                      
                        if ($Profile && isset($Profile["Profile_image"]) && file_exists($baseImagePath . $Profile["Profile_image"])) {
                            $ProfileImage = $baseImagePath . $Profile["Profile_image"];
                            echo "<img class='profileImage' src='{$ProfileImage}' alt=''>";
                        }
                    ?>
        </div>
    </div>

            <div class="user-details">
                <div class="input-box">
                    <span class="details">First Name: </span>
                    <span><p><?php echo $Profile["Firstname"];?></p></span>
                </div>

                <div class="input-box">
                    <span class="details">Last Name:</span>
                    <span><p><?php echo $Profile["Lastname"];?></p></span>
                </div>

                <div class="input-box">
                    <span class="details">Email: </span>
                    <span><p><?php echo $Profile["Email"];?></p></span>
                </div>

                <div class="input-box">
                    <span class="details">Phone Number:</span>
                    <span><p><?php echo $Profile["Phone_Num"];?></p></span>
                </div>

            </div>
    
                <div class="button">
                        <input type="submit" name="submit" id="submit" class="update" value="Update">
                </div>

      

    </div>
    
    <div class="show_Update">
            <div class="show_form">
                    <span class="Iconclose">
                        <img src="icons/icons8-go-back-50.png" class="Icon-close"alt="">
                    </span>

                        <div class="title_Update">
                            Profile Update
                        </div>

        <div class="split_show">

            <div class="Show_left">
                <p class="profile_title">Current Profile: </p>
                <div class="uploadimage_Update">
                <div class="imagePreview">
                    <?php
                        $baseImagePath = 'Profile_Image/';
                        $defaultImage = 'logo/OTS Logo.png'; 

                     
                        echo "<img class='defaultImage' src='{$defaultImage}' alt='Default Image'>";

                      
                        if ($Profile && isset($Profile["Profile_image"]) && file_exists($baseImagePath . $Profile["Profile_image"])) {
                            $ProfileImage = $baseImagePath . $Profile["Profile_image"];
                            echo "<img class='profileImage' src='{$ProfileImage}' alt=''>";
                        }
                    ?>
                    </div>
                </div>



                <div class="user-details1">
                        <div class="input-box">
                            <span class="details">First Name: </span>
                            <span><p><?php echo $Profile["Firstname"];?></p></span>
                        </div>

                        <div class="input-box">
                            <span class="details">Last Name:</span>
                            <span><p><?php echo $Profile["Lastname"];?></p></span>
                        </div>

                        <div class="input-box">
                            <span class="details">Email: </span>
                            <span><p><?php echo $Profile["Email"];?></p></span>
                        </div>

                        <div class="input-box">
                            <span class="details">Phone Number:</span>
                            <span><p><?php echo $Profile["Phone_Num"];?></p></span>
                        </div>

                </div>
            </div>

            <div class="Show_right">
            <p class="profile_title">Update Profile: </p>
                <form action="PHP_File/Profile_Update.php" method="post" autocomplete="off" enctype="multipart/form-data">

                        
                    <div class="uploadimage_Update">
                        <div class="imagePreview_update">
                            <?php
                                $baseImagePath = 'Profile_Image/';
                                $defaultImage = 'logo/OTS Logo.png'; 

                           
                                echo "<img class='defaultImage' src='{$defaultImage}' alt='Default Image'>";

                            
                                if ($Profile && isset($Profile["Profile_image"]) && file_exists($baseImagePath . $Profile["Profile_image"])) {
                                    $ProfileImage = $baseImagePath . $Profile["Profile_image"];
                                    echo "<img class='profileImage' src='{$ProfileImage}' alt=''>";
                                }
                            ?>
                    </div>
                            <input type="file" name="imageFiles" id="imageFile" accept="image/*">
                    </div>

            <script>
                document.getElementById('imageFile').addEventListener('change', function (event) {
               
                    const previewDiv = document.querySelector('.imagePreview_update');
                    previewDiv.innerHTML = '';
                
                 
                    for (let i = 0; i < event.target.files.length; i++) {
                        const file = event.target.files[i];
                        const img = document.createElement('img');
                    
                    
                        img.src = URL.createObjectURL(file);
                        img.onload = function () {
                            URL.revokeObjectURL(img.src); 
                        }
                    
                      
                        previewDiv.appendChild(img);
                    }
                });
            </script>

                <div class="user-details1">
                        <input type="hidden" name="userid" value="<?php echo $user_id; ?>">

                        <div class="input-box">
                            <span class="details">First Name:</span>
                            <input type="text" name="firstname" id="firstname" placeholder="Enter Your Firstname" value="<?php echo ucfirst($Profile["Firstname"]);?>" required pattern="[A-Za-z ]+" title="Only alphabetic characters are allowed" required>
                        </div>

                        <div class="input-box">
                            <span class="details">Last Name:</span>
                            <input type="text" name="lastname" id="lastname" placeholder="Enter Your Lastname" value="<?php echo ucfirst($Profile["Lastname"]);?>" required pattern="[A-Za-z ]+" title="Only alphabetic characters are allowed" required>
                        </div>

                        <script>
                            function capitalizeFirstLetter(inputId) {
                                var inputElement = document.getElementById(inputId);
                                var inputValue = inputElement.value;
                                inputElement.value = inputValue.charAt(0).toUpperCase() + inputValue.slice(1);
                            }

                            document.getElementById('firstname').addEventListener('blur', function () {
                                capitalizeFirstLetter('firstname');
                            });

                            document.getElementById('lastname').addEventListener('blur', function () {
                                capitalizeFirstLetter('lastname');
                            });
                        </script>

                        <div class="input-box">
                            <span class="details">Username: </span>
                            <input type="text" name="Username" id="username" placeholder="Enter Your Username" value="<?php echo $Profile["Username"];?>" required>

                        </div>

                        <div class="input-box">
                            <span class="details">Email: </span>
                            <input type="text" name="email" id="email" placeholder="Enter Your email" value="<?php echo $Profile["Email"];?>" required>
                            <script>
                                const emailInput = document.getElementById('email');

                                  emailInput.addEventListener('input', function() {
                                  const email = emailInput.value;
                                  if (email.includes('@') && email.includes('.com')) {
                                    emailInput.setCustomValidity('');
                                  } else {
                                    emailInput.setCustomValidity('Please enter a valid email address.');
                                  }
                                });
                            </script>
                        </div>

                        <div class="input-box">
                            <span class="details">Phone Number:</span>
                            <input type="text" name="PhoneNum" id="PhoneNum" placeholder="Enter Your Phone Number" value="<?php echo $Profile["Phone_Num"];?>" pattern="\+63[0-9]{10}" required>
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

                                </div>
                                
                                <div class="input-box">
                                <br>
                                    <input type="button" class="changepass" value="Change Password">
                                </div>
                                <script>
                                     document.querySelector(".changepass").addEventListener("click", function() {
                                        document.querySelector(".Changepass_popup").style.display = "flex";
                                        });
                                </script>
              
                    </div>
                </div>
            </div>
            
                <div class="button">
                    <input type="button"  id="submit" class="updatebtn" value="Update">
                    <script>
                         document.querySelector(".updatebtn").addEventListener("click", function() {
                            document.querySelector(".Confirmpass_popup").style.display = "flex";
                            });
                    </script>
                        <!-- <input type="submit" name="submit" id="submit" class="update" value="Update"> -->
                </div>

    <div class="Confirmpass_popup" id="<?php // echo $uniqueID; ?>">
        <div class="Confirmpass_form">
                <span class="IconcloseConfirmPass">
                    <img src="icons/icons8-go-back-50.png" class="Icon-close"alt="">
                </span>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                        document.querySelectorAll(".IconcloseConfirmPass").forEach(function(button) {
                        button.addEventListener("click", function(event) {
                        event.preventDefault();
                        document.querySelector(".Confirmpass_popup").style.display = "none";
                        
                                });
                            });
                        });
                    </script>

                <div class="title_confirmpass">
                    Update My Profile
                </div>
                
                <div class="input-box">
                            <span class="details">You must enter your current password to make changes to your profile </span>

                            <div class="password_merge">
                                <input type="password" name="Passwordtype" id="CurrentPassword" placeholder="Enter your current password" required>
                                <img src="logo/icons8-hideblack-50.png" alt="" class="icon2" id="hide1" onclick="pass1()">
                            </div>

                            <script>
                            var a
                            function pass1(){
                                if(a==1){
                                    document.getElementById('CurrentPassword').type='password';
                                    document.getElementById('hide1').src='logo/icons8-hideblack-50.png';
                                    a=0;
                                }
                                else{
                                    document.getElementById('CurrentPassword').type='text';
                                    document.getElementById('hide1').src='logo/icons8-eyeblack-50.png';
                                    a=1;
                                }
                            }
                        </script>
                </div>

                <div class="button">
                    <input type="submit" name="submit_change" id="submit" class="update" value="Update Profile">
                </div>
            
        </div>
    </div>
                        </form>

<div class="Changepass_popup" id="<?php // echo $uniqueID; ?>">
        <div class="Changepass_form">
        <form action="PHP_File/Profile_Update.php" method="post" autocomplete="off" enctype="multipart/form-data">
        <input type="hidden" name="userid" value="<?php echo $user_id; ?>">
                <span class="IconclosePass">
                    <img src="icons/icons8-go-back-50.png" class="Icon-close"alt="">
                </span>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                        document.querySelectorAll(".IconclosePass").forEach(function(button) {
                        button.addEventListener("click", function(event) {
                        event.preventDefault();
                        document.querySelector(".Changepass_popup").style.display = "none";
                        
                        // remove id parameter from the URL
                            // var url = window.location.toString();
                            //         if (url.indexOf("?") > 0) {
                            //             var clean_url = url.substring(0, url.indexOf("?"));
                            //             window.history.replaceState({}, document.title, clean_url);
                            //         }
                                });
                            });
                        });
                    </script>

                <div class="title_changepass">
                    Change Password
                </div>
                
                <div class="input-box">
                            <span class="details">Current Password: </span>

                            <div class="password_merge">
                                <input type="password" name="CurrentPassword" id="CurrentPassword" placeholder="Current Password" required>
                                <img src="logo/icons8-hideblack-50.png" alt="" class="icon2" id="hide1" onclick="pass1()">
                            </div>

                            <script>
                            var a
                            function pass1(){
                                if(a==1){
                                    document.getElementById('CurrentPassword').type='password';
                                    document.getElementById('hide1').src='logo/icons8-hideblack-50.png';
                                    a=0;
                                }
                                else{
                                    document.getElementById('CurrentPassword').type='text';
                                    document.getElementById('hide1').src='logo/icons8-eyeblack-50.png';
                                    a=1;
                                }
                            }
                        </script>
                </div>

                <div class="input-box">
                            <span class="details">New Password: </span>

                            <div class="password_merge">
                                <input type="password" name="Password" id="Password" placeholder="New Password" required >
                                <img src="logo/icons8-hideblack-50.png" alt="" class="icon2" id="hide2" onclick="pass2()">
                            </div>

                            <script>
                            var a
                            function pass2(){
                                if(a==1){
                                    document.getElementById('Password').type='password';
                                    document.getElementById('hide2').src='logo/icons8-hideblack-50.png';
                                    a=0;
                                }
                                else{
                                    document.getElementById('Password').type='text';
                                    document.getElementById('hide2').src='logo/icons8-eyeblack-50.png';
                                    a=1;
                                }
                            }
                        </script>
                </div>
                        <div class="input-box">
                            <span class="details">Confirm New Password:</span>

                            <div class="confirmpassword_merge">
                                <input type="password" name="confirmpassword" id="confirmpassword" placeholder="Confirm New Password" required>
                                <img src="logo/icons8-hideblack-50.png" alt="" class="icon3" id="hide3" onclick="pass3()">
                            </div>
                            <script>
                        var a
                        function pass3(){
                            if(a==1){
                                document.getElementById('confirmpassword').type='password';
                                document.getElementById('hide3').src='logo/icons8-hideblack-50.png';
                                a=0;
                            }
                            else{
                                document.getElementById('confirmpassword').type='text';
                                document.getElementById('hide3').src='logo/icons8-eyeblack-50.png';
                                a=1;
                            }
                        }
                    </script>
                        </div>
                                <!--...your payment form fields...-->
                <div class="button">
                    <input type="submit" name="change_pass" id="submit" value="Change Password">
                </div>
                </form>
        </div>
    </div>


            
        </div>

    </div>
 <script>
            document.querySelector(".update").addEventListener("click", function() {
            document.querySelector(".show_Update").style.display = "flex";
            });

            document.querySelector(".Icon-close").addEventListener("click", function() {
            document.querySelector(".show_Update").style.display = "none";
            });

        </script>


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
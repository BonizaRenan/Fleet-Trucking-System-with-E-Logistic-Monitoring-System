<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.js"></script>
    <title>Register</title>
</head>
<body>
    <div class="container">
        <div class="backbtn">
            <input type="button" value="Back" onClick="location.href='index.php'">
        </div>
        <div class="title">
            Registration
        </div>
        <form action="PHP_file/RegisterPHP.php" method="post" autocomplete="off" onsubmit="return validateForm()">

            <div class="user-details">
            <div class="input-box">
                <span class="details">First Name</span>
                <input type="text" name="Firstname" id="Firstname" placeholder="Enter First Name" required pattern="[A-Za-z ]+" title="Only alphabetic characters are allowed">
            </div>

            <div class="input-box">
                <span class="details">Last Name</span>
                <input type="text" name="Lastname" id="Lastname" placeholder="Enter Last Name" required pattern="[A-Za-z ]+" title="Only alphabetic characters are allowed">
            </div>

            <script>
                function capitalizeFirstLetter(inputId) {
                    var inputElement = document.getElementById(inputId);
                    var inputValue = inputElement.value;
                    inputElement.value = inputValue.charAt(0).toUpperCase() + inputValue.slice(1);
                }

                document.getElementById('Firstname').addEventListener('blur', function () {
                    capitalizeFirstLetter('Firstname');
                });

                document.getElementById('Lastname').addEventListener('blur', function () {
                    capitalizeFirstLetter('Lastname');
                });
            </script>

                <div class="input-box">
                    <span class="details">Username</span>
                    <input type="text" name="Username" id="Username" placeholder="Enter Username" required>
                </div>

                <div class="input-box">
                    <span class="details">Email Address</span>
                    <input type="text" name="Email" id="email" placeholder="Enter Email Address" required>
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
                    <span class="details">Phone Number</span>
                    <input type="text" name="PhoneNum" id="PhoneNum" value="+63" required pattern="\+63[0-9]{10}">
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

                        <div class="password_merge">
                            <input type="password" name="Password" id="Password" placeholder="Enter Password" required>
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
                        <input type="password" name="confirmpassword" id="confirmpassword" placeholder="Confirm Password" required>
                        <img src="logo/icons8-hideblack-50.png" alt="" class="icon3" id="hide2" onclick="pass2()">
                    </div>

                    <script>
                        var a
                        function pass2(){
                            if(a==1){
                                document.getElementById('confirmpassword').type='password';
                                document.getElementById('hide2').src='logo/icons8-hideblack-50.png';
                                a=0;
                            }
                            else{
                                document.getElementById('confirmpassword').type='text';
                                document.getElementById('hide2').src='logo/icons8-eyeblack-50.png';
                                a=1;
                            }
                        }
                    </script>
                </div> 
                
               <script>
                    function validateForm() {
                        var password = document.getElementById('Password').value;
                        var confirmPassword = document.getElementById('confirmpassword').value;

                    if (password !== confirmPassword) {
                        document.getElementById('confirmpassword').setCustomValidity("Passwords do not match.");
                        return false;
                    } else {
                        document.getElementById('confirmpassword').setCustomValidity('');
                        return true;
                    }
                }
                </script>
                

                <div class="user-type">
                <input type="hidden" id="Usertype" name="Usertype" value="client">
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

            <div class="terms">
                <input type="checkbox" name="agree" id="agree" required>
                <a href="TermsandCondition.php" target="_blank" id="termsLink">Terms & Conditions</a>
                <script>
                    document.getElementById("agree").addEventListener("change", function() {
                        var termsLink = document.getElementById("termsLink");
                        if (this.checked) {
                            termsLink.target = "_blank"; 
                        } else {
                            termsLink.target = "_self"; 
                        }
                    });
                </script>
            </div>
                
            <div class="button">
                <input type="submit" name="submit" id="submit" value="Register">
            </div>

            <?php
                if (isset($_GET['addMsg'])) {
                    $message = $_GET['addMsg'];

                    if ($message == 'Username or Email Has Already Taken') {
                        $class = 'error-msg'; // CSS class for error messages
                    } 
                    // elseif ($message == 'Registered Successfully') {
                    //     $class = 'success-msg'; // CSS class for success messages
                    // }
                ?>
                      <p class="addMsg <?php echo $class; ?>"><?php echo $message; ?></p>
                <?php } ?>
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
            </script>
        </form>
    </div>
</body>
</html>
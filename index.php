<?php
include ('PHP_file/LoginPHP.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="login.css">
    <title>LogIn</title>
</head>
<body onload="window.history.forward();">
<header>
        <div class="logo">
            <img class="logo2" src="logo/OTS Logo.png" alt="">
            <h2>Masbate Trucking</h2>
        </div>

        <nav class="navigation">
            <ul>
                <!-- <div class="nav1"> -->
                <li><a href="About.php">About</a></li>
                <li><a href="location.php">Location</a></li>
                <!-- </div> -->
                <li><button class="signup" onClick="location.href='Register.php'"> Sign Up</button></li>
            </ul>
        </div>
        </nav>
</header>

    <div class="login">
            <h1>Login</h1>
            <form action="PHP_file/LoginPHP.php<?php echo isset($_GET['redirect']) ? '?redirect=' . $_GET['redirect'] : ''; ?>" method="POST" autocomplete="off">
            <div class="input-box">
                    <input type="type" name="Email" required>
                    <img src="logo/icons8-user-male-50.png" alt="" class="icon">
                    <label>Username or Email</label>
                </div>

                <div class="input-box">
                        <input id="password"type="password" name="password" required>
                        <img src="logo/icons8-hide-50.png" alt="" class="icon2" id="hide" onclick="pass()">
                        <img src="logo/icons8-password-50.png" alt="" class="icon">
                        <label>Password</label>
                    <script>
                        var a
                        function pass(){
                            if(a==1){
                                document.getElementById('password').type='password';
                                document.getElementById('hide').src='logo/icons8-hide-50.png';
                                a=0;
                            }
                            else{
                                document.getElementById('password').type='text';
                                document.getElementById('hide').src='logo/icons8-eye-50.png';
                                a=1;
                            }
                        }
                    </script>
                <div class="remember">
                    <a href="Forgotpassword.php">Forgot Password?</a> 
                </div>

                <button type="Submit" class="btn" name="submit">Log in</button>
                </div>
            </form>
    </div>

<!-- Footer -->
<div class="footer">
    <div class="footer-content">
        <div class="footer-logo">
        <img class="logo2" src="logo/OTS Logo.png" alt="">
            <h2>Masbate Trucking</h2>
        </div>
        <div class="footer-contact">
            Email: oliver5335janet@gmail.com<br>
            Phone: +639219775000
        </div>
        <div class="footer-rights">
            &copy; 2023 Masbate Trucking. All rights reserved.
        </div>
    </div>
</div>
    
</body>
</html>
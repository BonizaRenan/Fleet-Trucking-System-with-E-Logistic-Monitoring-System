<?php
session_start();

// Connection to the database
$conn = mysqli_connect("localhost", "root", "", "transaction");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

   
    if ($new_password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
        exit();
    }

   
    if (!isset($_SESSION['email_for_Forgot_verification'])) {
        echo "Email not set in session!";
        exit();
    }

    $email = $_SESSION['email_for_Forgot_verification'];

  
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    
    $stmt = $conn->prepare("UPDATE account SET Password = ? WHERE Email = ?");
    $stmt->bind_param("ss", $new_password, $email);

    if ($stmt->execute()) {
        echo "<script>
                alert('Password successfully updated!');
                window.location.href = 'index.php';
              </script>";
        unset($_SESSION['email_for_Forgot_verification']); 
    } else {
        echo "Error updating password: " . $stmt->error;
    }

    $stmt->close();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Newpassword.css">
    <title>New Password</title>
    <script>
        function validatePasswords() {
            const newPassword = document.getElementById("new_password");
            const confirmPassword = document.getElementById("confirm_password");

            if (newPassword.value !== confirmPassword.value) {
                alert("Passwords do not match!");
                return false;
            }
            return true;
        }

        function togglePasswordVisibility(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        icon.src = "logo/icons8-eyeblack-50.png";
        passwordInput.classList.add("show-password");
    } else {
        passwordInput.type = "password";
        icon.src = "logo/icons8-hideblack-50.png";
        passwordInput.classList.remove("show-password");
    }
}
    </script>
</head>
<body>
<form action="#" method="post" class="email-verification-form" onsubmit="return validatePasswords();">
    <p class="title">New Password</p>
    <div class="input-verification">
        <div class="input-container">
            <input type="password" id="new_password" name="new_password" placeholder="Enter New Password" required>
            <img src="logo/icons8-hideblack-50.png" alt="" class="icon3" id="hide1" onclick="togglePasswordVisibility('new_password', 'hide1')">
            <br>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Enter Confirm New Password" required>
            <img src="logo/icons8-hideblack-50.png" alt="" class="icon3" id="hide2" onclick="togglePasswordVisibility('confirm_password', 'hide2')">
        </div>
    </div>
    <input type="submit" value="Change Password">
</form>
</body>
</html>
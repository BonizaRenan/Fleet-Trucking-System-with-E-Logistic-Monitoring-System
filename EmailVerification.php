<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="EmailVerification.css">
    <title>Email Verification</title>
</head>
<body>
<form action="PHP_file/verifyemail.php" method="post" class="email-verification-form">
        <p class="title">Email Verification</p>
        <div class="input-verification">

            <div class="image-container">
                <img src="logo/googlelogo.png" alt="Google Logo">
            </div>
            
            <div class="input-container">
                <input type="number" name="verification_code" placeholder="Enter verification code" required>
            </div>
        </div>
        <input type="submit" value="Verify">
    </form>
</body>
</html>
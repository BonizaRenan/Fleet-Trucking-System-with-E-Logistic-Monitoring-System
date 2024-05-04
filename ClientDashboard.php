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
$Profile = mysqli_fetch_assoc($select);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="ClientDashboard.css">


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
        
        <div class="box">
            <div class="greetings">
                <p class="titlegreet">Good Day, <?= $Profile['Firstname'] . ' ' . $Profile['Lastname']; ?>.</p>
                <p class="clientgreet">Welcome to Masbate Trucking!</p>
            </div>

            <div class="container" id="container1">
                <div class="module">

                    <div class="button-fillup">
                        <div class="border-image">
                            <img src="logo/packagebox.png" alt="Package Box Image">
                            <button onClick="location.href='ClientFillUpPackage.php'">Fill-Up Package</button>
                        </div>
                    </div>

                    <div class="description">
                        <p class="title">Fill-Up Package</p>
                        <p class="info">The Fill Up Package module contains the Package Information, Receiver's Information, and Payment Method that the client needs to fill up.</p>
                        <p class="info">The Package Information section needs information about the package's Image, Package Name, Package Type, Package Weight(per unit), and Package Quantity.</p>
                        <p class="info">The Receiver's Information section needs information about the Receiver's Full Name, Email Address, Phone Number, and Drop-off Address.</p>
                    </div>

                </div>

                <!-- Repeat the structure for additional modules -->
            </div>

            <div class="container2" id="container2">
                <div class="module">

                    <div class="description">
                        <p class="title">Transaction Record</p>
                        <p class="info">The Transaction Record module contains all the details of past and current transactions of the client.</p>
                        <p class="info">This module show the package's Status, Date and Time the transaction started, Client's Name, Client's Phone Number, Client's Email, Package ID, Package Name, Receiver's Name, Receiver's Phone Number, Receiver's Email, and Receiver's Address.</p>
                        <div class="info">Clicking the "Details" button will show information about the Package Information, Personal Information of the Client and Receiver, and the Payment Transaction.</div>
                    </div>

                    <div class="button-fillup">
                        <div class="border-image">
                            <img src="logo/transactionimage.png" alt="Package Box Image">
                            <button onClick="location.href='ClientTransactionRecord.php'">Transaction Record</button>
                        </div>
                    </div>

                </div>
            </div>
<script>
    // Add the following JavaScript to control visibility with a delay

document.addEventListener('DOMContentLoaded', function() {
    // Select the containers
    const container1 = document.getElementById('container1');
    const container2 = document.getElementById('container2');

    // Fade in container 1
    container1.style.animation = 'fadeIn 1s ease-in-out';
    container1.style.opacity = '1';

    // Set a delay for fading in container 2
    setTimeout(function() {
        container2.style.animation = 'fadeIn 1s ease-in-out';
        container2.style.opacity = '1';
    }, 400); // 1000 milliseconds delay (adjust as needed)
});
</script>
        </div>
         
           

</body>
</html>
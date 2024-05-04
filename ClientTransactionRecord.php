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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ClientTransactionRecord.css">
    <title>Transaction Record</title>
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

<div class="container">
    <div class="Record">
        <div class="backbtn">
            <input type="button" value="Back" onClick="location.href='ClientDashboard.php'">
        </div>
        <div class="title">
            Transaction Record
        </div>
        <div class="statusIndeicatortitle">Status Color:</div>
    <div class="statusindicator">
            <div class="statusindicator1">
                <p class="ColorIndicator" style="color: red;"> ■ </p><p class="nameIndicator">Review Package</p>
                <p class="ColorIndicator" style="color: skyblue;"> ■ </p><p class="nameIndicator">Payment Price/ Refund Payment</p>
            </div>
            <div class="statusindicator1">
                <p class="ColorIndicator" style="color: blue;"> ■ </p><p class="nameIndicator"> Payment Confirmed<br>/Declined/Refunded </p>
                <p class="ColorIndicator" style="color: Orange;"> ■ </p><p class="nameIndicator"> Pending Package</p>
            </div>
            <div class="statusindicator1">
                <p class="ColorIndicator" style="color: #4B0082;"> ■ </p><p class="nameIndicator">Package On-Site</p>
                <p class="ColorIndicator" style="color: Yellow;"> ■ </p><p class="nameIndicator">Ongoing Package</p>
            </div>
            <div class="statusindicator1">
                <p class="ColorIndicator" style="color: #32CD32;"> ■ </p><p class="nameIndicator">Package Delivered</p>
            </div>
    </div>
    <div class="table-container">        
        <table class="Table">
            <tr>
                <th></th>
                <th>Status</th>
                <th></th>
                <th>Date and Time</th>
                <th>Client Name</th>
                <th>Client Cp.Num</th>
                <th>Client Email</th>
                <th>Package ID</th>
                <th>Package Name</th>
                <th>Receiver Name</th>
                <th>Receiver CP.Num</th>
                <th>Receiver Email</th>
                <th>Receiver Address</th>        
            </tr>
            <tr>
                <?php
                // Database connection
                $conn = mysqli_connect("localhost", "root", "", "transaction");
                if ($conn->connect_error) {
                    die('Connection failed: ' . $conn->connect_error);
                }
                $searchQuery = "";

              
                if(isset($_GET['search'])) {
                    $searchQuery = $_GET['search'];
                
                  
                    $searchQuerySafe = mysqli_real_escape_string($conn, $searchQuery);
                }
                
               
                $sql = "SELECT * FROM client_package WHERE Client_ID = $user_id";
                
             
                if($searchQuery !== "") {
                    $sql .= " AND (PackageNameCode LIKE '%$searchQuerySafe%' OR Package_Name LIKE '%$searchQuerySafe%' OR Receiver_Name LIKE '%$searchQuerySafe%' OR Receiver_PhoneNum LIKE '%$searchQuerySafe%' OR Receiver_Email LIKE '%$searchQuerySafe%')";
                }
                
                $sql .= " ORDER BY id DESC";

                

                $result = $conn->query($sql);

                
                if ($result->num_rows > 0) {

                        $counter=1;
                        while ($row = $result->fetch_assoc()) { 
                        $Transaction_ID = $row['id'];

                        $stmt1 = $conn->prepare("SELECT * FROM payment_transaction WHERE Package_ID = ?");
                        $stmt1->bind_param("i", $Transaction_ID);
                        $stmt1->execute();
                    
                        $result1 = $stmt1->get_result();
                        $paymentDetails = $result1->fetch_assoc();


                        
                        $statusReview = $row['Status_Review'];
                        $Clientpayment = isset($paymentDetails['Client_Confirm']) ? $paymentDetails['Client_Confirm'] : null;
                        $PaymentConfirm = $row['Payment_Confirmation'];
                        $Arriving_Package = $row['Arriving_Package'];
                        $ClientDecline = isset($paymentDetails['Client_Confirm']) ? $paymentDetails['Client_Confirm'] : null;
                        $ClientRefund = isset($paymentDetails['Refund_Confirmation']) ? $paymentDetails['Refund_Confirmation'] : null;
                        
                       
                        $statusColor = 'white';
                        $statusNames = 'None';
                        $statusFontColor = 'black';
                        if($statusReview == 'Pending'){
                            $statusColor = 'red';
                            $statusNames = 'Review Package';
                            $statusFontColor = 'white';
                        }
                        if($statusReview == 'Decline'){
                            $statusColor = 'red';
                            $statusNames = 'Package Declined';
                            $statusFontColor = 'white';
                        }
                        elseif($Clientpayment == 'Pending'){
                            $statusColor = 'skyblue';
                            $statusNames = 'Payment Price';
                        }

                        elseif($ClientDecline == 'Decline'){
                            $statusColor = 'skyblue';
                            $statusNames = 'Decline Payment';
                        }

                        elseif($ClientRefund == 'Refund'){
                            $statusColor = 'skyblue';
                            $statusNames = 'Refund Payment';
                        }

                        elseif($PaymentConfirm == 'Pending'){
                            $statusColor = 'blue';
                            $statusNames = 'Payment Confirmed';
                            $statusFontColor = 'white';
                        }

                        elseif($PaymentConfirm == 'Decline'){
                            $statusColor = 'blue';
                            $statusNames = 'Payment Declined';
                            $statusFontColor = 'white';
                        }
                        
                        elseif($PaymentConfirm == 'Reject'){
                            $statusColor = 'blue';
                            $statusNames = 'Payment Declined';
                            $statusFontColor = 'white';
                        }

                        elseif($PaymentConfirm == 'Refund'){
                            $statusColor = 'blue';
                            $statusNames = 'Payment Refunded ';
                            $statusFontColor = 'white';
                        }

                        elseif($Arriving_Package == 'Pending'){
                            $statusColor = 'Orange';
                            $statusNames = 'Pending Package';
                        }
                        elseif($Arriving_Package == 'On-Site'){
                            $statusColor = '#4B0082';
                            $statusNames = 'Package On-Site';
                            $statusFontColor = 'white';
                        }
                        elseif($Arriving_Package == 'On-Going'){
                            $statusColor = 'Yellow';
                            $statusNames = 'Ongoing Package';
                        }
                        elseif($Arriving_Package == 'Delivered'){
                            $statusColor = '#32CD32';
                            $statusNames = 'Package Delivered';
                        }
                    ?>

                <td><?php echo $counter++; ?></td>
                <td class="colorstatus" style="background-color: <?php echo $statusColor; ?>;"><p style="color: <?php  echo $statusFontColor;?> ;"><?php echo $statusNames; ?></p></td>
                <td class="Show"><?php echo "<a href='ClientPackageInfo.php?Transaction_ID={$Transaction_ID}' class='Show' >Details</a>"?><?php ;?></td>
                <td><?php echo $row["Date_Time"]; ?></td>
                <td><?php echo $Profile["Firstname"]; ?> <?php echo $Profile["Lastname"]; ?></td>
                <td><?php echo $Profile["Phone_Num"]; ?></td>
                <td><?php echo $Profile["Email"]; ?></td>
                <td><?php echo strtoupper($row["PackageNameCode"]); ?></td>
                <td><?php echo $row["Package_Name"]; ?></td>
                <td><?php echo $row["Receiver_Name"]; ?></td>
                <td><?php echo $row["Receiver_PhoneNum"]; ?></td>
                <td><?php echo $row["Receiver_Email"]; ?></td>
                <td><?php echo $row["Receiver_Address"]; ?></td>
                            
            </tr>
        <?php }?>
        </table>
        <?php } else {
        // echo("No Record");
    } ?>
    </div>
        <br>

    </div>
</body>
</html>
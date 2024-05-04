<?php
session_start();
date_default_timezone_set('Asia/Manila');

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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="AdminDashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Admin Dashboard</title>
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
                
                // Display user name
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
        
               
        <?php
            $conn = mysqli_connect("localhost", "root", "", "transaction");
          

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $sql1 = "SELECT COUNT(*) as package FROM client_package WHERE Arriving_Package='On-Site'";
            $package = $conn->query($sql1);
            if ($package) {
                $row = $package->fetch_assoc();
                $total_package = $row['package'];
            } else {
                $total_package = "Error";
            }

            $sql2 = "SELECT COUNT(*) as truck FROM truck"; 
            $truck = $conn->query($sql2);

            if ($truck) {
                $row = $truck->fetch_assoc();
                $total_truck = $row['truck'];
            } else {
                $total_truck = "Error"; 
            }

            $sql3 = "SELECT COUNT(*) as staff FROM account WHERE user_type='staff'";
            $staff = $conn->query($sql3);

            if ($staff) {
                $row = $staff->fetch_assoc();
                $total_staff = $row['staff'];
            } else {
                $total_staff = "Error";
            }

        ?>



            
        <div class="Dashboard">
            <p class="DashboardTitle">Dashboard</p>

            <div class="formbox">
                <div class="Items package">
                <p class="numbers"><?php echo $total_package;?></p>
                    <h3>Number of Packages</h3>
                    <img src="logo/Dash_Package.png" alt="">
                    <input type="button" value="More Info.."class="package_btn" onclick=" window.location.href = 'AdminInventoryPackage.php?PackageStatus=On-site';">
                </div>
                
                <div class="Items truck">
                    <p class="numbers"><?php echo $total_truck;?></p>
                    <h3>Number of Vehicles</h3>
                    <img src="logo/Dash_truck.png" alt="" >
                    <input type="button" value="More Info.." class="truck_btn" onclick=" window.location.href = 'AdminTruckInventory.php';">
                </div>

                <div class="Items staff">
                <p class="numbers"><?php echo $total_staff;?></p>
                    <h3>Number of Staff</h3>
                    <img src="logo/Dash_Staff.png" alt="">
                    <input type="button" value="More Info.." class="staff_btn" onclick=" window.location.href = 'AdminStaffAccount.php';">
                </div>
            </div>
        </div>
         <!-- <h1 class="Customer">Most Loyal Client</h1> -->

         <?php 
                 $currentMonth = date('m');
                 $currentYear = date('Y');
                 
                 $sql = "SELECT *, COUNT(Client_name) as total_deliveries 
                         FROM client_package 
                         WHERE Arriving_Package = 'Delivered' 
                         AND MONTH(Date_Time) = $currentMonth 
                         AND YEAR(Date_Time) = $currentYear
                         GROUP BY Client_name 
                         ORDER BY total_deliveries DESC 
                         LIMIT 10";

         $result = $conn->query($sql);
         
         $clients = [];
         $deliveries = [];
         $monthName = date('F', strtotime("now"));
         $year = date('Y', strtotime("now"));

        //  $clients = [];
        // $deliveries = [];
        $details = [];

         if ($result->num_rows > 0) {
             while($row = $result->fetch_assoc()) {
                 $clients[] = $row["Client_name"];
                 $deliveries[] = $row["total_deliveries"];

              
        $clientName = $row["Client_name"];
            // $detailsQuery = "SELECT * FROM client_package 
            //              WHERE Client_name = '$clientName' 
            //              AND Arriving_Package = 'Delivered' 
            //              AND MONTH(Date_Time) = $currentMonth 
            //              AND YEAR(Date_Time) = $currentYear";
            $detailsQuery = "
                    SELECT cp.*, p.* 
                    FROM client_package cp
                    INNER JOIN payment_transaction p ON cp.id = p.Package_ID 
                    WHERE cp.Client_name = '$clientName' 
                    AND cp.Arriving_Package = 'Delivered' 
                    AND MONTH(cp.Date_Time) = $currentMonth 
                    AND YEAR(cp.Date_Time) = $currentYear
            ";


        $detailsResult = $conn->query($detailsQuery);
        $clientDetails = [];

        if ($detailsResult->num_rows > 0) {
            while ($detailsRow = $detailsResult->fetch_assoc()) {
                $clientDetails[] = [
                    "name" => $detailsRow["Client_name"],
                    "Date Time" => $detailsRow["Date_Time"],
                    "Package Name" => $detailsRow["Package_Name"],
                    "Receiver Name" => $detailsRow["Receiver_Name"],

                    "Payment Method" => $detailsRow["Payment_Method"],
                    "Price to Payment" => $detailsRow["Price_Payment"],
                    "Package Status" => $detailsRow["Arriving_Package"]
                ];
            }
        }
        $details[$clientName] = $clientDetails;
                 
             }
         }
            ?>

<div class="box"> 
    <div id="printArea"> 
        <p class="TitleClient">Number of Client's Deliveries for <?php echo date('F Y');?></p>

            <canvas id="loyalClientsChart"></canvas>

            <script>
                var ctx = document.getElementById('loyalClientsChart').getContext('2d');
                var clientDetails = <?php echo json_encode($details); ?>;
              
                var colors = [
                    'rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)', 'rgba(255, 99, 99, 0.2)', 'rgba(54, 162, 162, 0.2)',
                    'rgba(255, 206, 206, 0.2)', 'rgba(75, 192, 75, 0.2)'
                ];
            
                var borderColors = [
                    'rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)', 'rgba(255, 99, 99, 1)', 'rgba(54, 162, 162, 1)',
                    'rgba(255, 206, 206, 1)', 'rgba(75, 192, 75, 1)'
                ];
            
                var chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: <?php echo json_encode($clients); ?>,
                        datasets: [{
                            label: '# of Deliveries',
                            data: <?php echo json_encode($deliveries); ?>, 
                            backgroundColor: colors,
                            borderColor: borderColors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            
                chart.canvas.onclick = function(event) {
                    var activePoints = chart.getActiveElements();
                
                        if (activePoints.length > 0) {
                            var clickedBarIndex = activePoints[0].index;
                            var clickedLabel = chart.data.labels[clickedBarIndex];
                            console.log("Clicked on:", clickedLabel);
                        
                            var records = clientDetails[clickedLabel];
                        
                            if (records && records.length > 0) {
                                console.log("Found records:", records);
                                showPopup(records);
                            } else {
                                console.log("No records found for clicked label");
                            }
                        }
                };
            
                    function showPopup(records) {
                        var tableContent = '';
                    
                        records.forEach(record => {

                            tableContent += `
                            <tr>
                                <td>${record['Date Time'] || 'N/A'}</td>
                                <td>${record.name || 'N/A'}</td>
                                <td>${record['Package Name'] || 'N/A'}</td>
                                <td>${record['Receiver Name'] || 'N/A'}</td>
                                <td>${record['Payment Method'] || 'N/A'}</td>
                                <td>₱${record['Price to Payment'] || 'N/A'}</td>
                                <td>${record['Package Status'] || 'N/A'}</td>
                            </tr>
                            `;
                        });
                    
              
                                var tableBody = document.querySelector('.popupAnalytics table tbody');
                                if (!tableBody) {
                                    tableBody = document.createElement('tbody');
                                    document.querySelector('.popupAnalytics table').appendChild(tableBody);
                                }
                                tableBody.innerHTML = tableContent;
                                
                              
                                var clientNameElement = document.getElementById("transactionTitle");
                                clientNameElement.textContent = "Transactions for " + records[0].name;
                                
                              
                                var popupAnalytics = document.querySelector('.popupAnalytics');
                                popupAnalytics.style.display = 'block';
                            }
                </script>
            
                                    
            <div class="allTransactions">
           
            </div>
            <Script>
                
                function populateAllTransactions() {
                    const allTransactionsDiv = document.querySelector('.allTransactions');
                    allTransactionsDiv.innerHTML = '';  
                    
                
                    for(let clientName in clientDetails) {
                        let transactions = clientDetails[clientName];
                    
                        let tableContent = `
                            <div class="clientTransactions">
                                <h3>Transactions for ${clientName}</h3>  <!-- Title for each client's transactions -->
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Date Time:</th>
                                            <th>Client Name:</th>
                                            <th>Package Name:</th>
                                            <th>Receiver Name:</th>
                                            <th>Payment Method:</th>
                                            <th>Payment Price:</th>
                                            <th>Package Status:</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        `;
                    
                        transactions.forEach(record => {
                            tableContent += `
                            <tr>
                                <td>${record['Date Time'] || 'N/A'}</td>
                                <td>${record.name || 'N/A'}</td>
                                <td>${record['Package Name'] || 'N/A'}</td>
                                <td>${record['Receiver Name'] || 'N/A'}</td>
                                <td>${record['Payment Method'] || 'N/A'}</td>
                                <td>₱${record['Price to Payment'] || 'N/A'}</td>
                                <td>${record['Package Status'] || 'N/A'}</td>
                            </tr>
                            `;
                        });
                    
                        tableContent += `
                                    </tbody>
                                </table>
                            </div>  <!-- End of individual client's transactions -->
                        `;
                    
                        allTransactionsDiv.innerHTML += tableContent;
                    }
                }
            
               
                populateAllTransactions();
            </Script>
    </div>

<div class="popupAnalytics">
    <div class="formAnalytics">
            <span class="IconcloseAnalytics">
                <img src="icons/icons8-go-back-50.png" class="Icon-close"alt="">
            </span>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll(".IconcloseAnalytics").forEach(function(button) {
                button.addEventListener("click", function(event) {
                event.preventDefault();
                document.querySelector(".popupAnalytics").style.display = "none";
                
                
                // var url = window.location.toString();
                // if (url.indexOf("?") > 0) {
                //         var clean_url = url.substring(0, url.indexOf("?"));
                //         window.history.replaceState({}, document.title, clean_url);
                //     }
                }); 
            });
            });
                    </script>
    <div class="title_transaction" id="transactionTitle">
    Transactions for 
    </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Date Time:</th>
                        <th>Client Name:</th>
                        <th>Package Name:</th>
                        <th>Receiver Name:</th>
                        <th>Payment Method:</th>
                        <th>Payment Price:</th>
                        <th>Package Status:</th>
                    </tr>
                </thead>
                <tbody>
                 
                </tbody>
            </table>
            <br>
        </div>
    </div>
</div>


<button id="print" class="AnalyticsPrint">Print</button>

<script>
    let printBtn = document.querySelector("#print");
    
    printBtn.addEventListener("click", function() {
        window.print(); 
    });

</script>
</body>
</html>
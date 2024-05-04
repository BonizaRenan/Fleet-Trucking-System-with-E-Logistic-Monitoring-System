<?php
session_start();

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
    <link rel="stylesheet" href="AdminTransactionRecordHistory.css">
    <!-- <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script> -->
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.0.3/html5-qrcode.min.js"></script>
    <title>Transaction History</title>
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

        <script>
            document.querySelector(".Search_btn").addEventListener("click", function() {
            const searchQuery = document.getElementById('search_input').value;
            window.location.href = window.location.pathname + '?search=' + encodeURIComponent(searchQuery);
            });
        </script>


        <?php
        
            $conn = mysqli_connect("localhost", "root", "", "transaction");
            $sql = "SELECT * FROM client_package WHERE Arriving_Package = 'Delivered'";
            
            if (isset($_GET['qrtext'])) {
                $qrtext = mysqli_real_escape_string($conn, $_GET['qrtext']);
                $sql .= " AND (	QrCodeText LIKE '%$qrtext%' )";
            }
      
            if (isset($_GET['PackageStatus']) && $_GET['PackageStatus'] == 'All') {
              
              

                if (isset($_GET['search'])) {
                    $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);
                    $sql .= " AND (Package_Name LIKE '%$searchTerm%' OR PackageNameCode LIKE '%$searchTerm%' OR Client_name LIKE '%$searchTerm%' OR Receiver_Name LIKE '%$searchTerm%' OR Payment_Method LIKE '%$searchTerm%' OR Package_Type LIKE '%$searchTerm%')";
                }

            } else {
             
            
                if (isset($_GET['search'])) {
                    $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);
                    $sql .= " AND (Package_Name LIKE '%$searchTerm%' OR PackageNameCode LIKE '%$searchTerm%' OR Client_name LIKE '%$searchTerm%' OR Receiver_Name LIKE '%$searchTerm%' OR Payment_Method LIKE '%$searchTerm%' OR Package_Type LIKE '%$searchTerm%')";
                }
            
            }

            $Search = $conn->query($sql);
        ?>

         <!-- Search bar End -->
    
<div class="box">
<div class="qrscanner_btn">
            <input type="button" class="scanner_btn" id="startScannerButton" value="QR Scanner">
        </div>

        <script>
                        document.addEventListener("DOMContentLoaded", function() {
                        document.querySelectorAll(".scanner_btn").forEach(function(button) {
                        button.addEventListener("click", function(event) {
                        document.querySelector(".qrscanner_Popup").style.display = "flex";
                        
                        });
                    });
                    });
        </script>

        <div class="qrscanner_Popup">
            <div class="add_form">
                <span class="Iconclose">
                    <img src="icons/icons8-go-back-50.png" class="Icon-close" alt="">
                </span>
                <script>
                        document.addEventListener("DOMContentLoaded", function() {
                        document.querySelectorAll(".Iconclose").forEach(function(button) {
                        button.addEventListener("click", function(event) {
                        event.preventDefault();
                        document.querySelector(".qrscanner_Popup").style.display = "none";
                        
                     
                        var url = window.location.toString();
                        if (url.indexOf("?") > 0) {
                                var clean_url = url.substring(0, url.indexOf("?"));
                                window.history.replaceState({}, document.title, clean_url);
                            }
                        });
                    });
                    });
                    </script>
                <div class="title_Review">QR Scanning</div>
                <main>
                    <div id="reader"></div>
                    <div id="result"></div>
                </main>

                <script>
            const scanner = new Html5QrcodeScanner('reader',{
                fps: 20,
            });

            scanner.render(success, error)

            function success(result){
              
                let newLocation = window.location.pathname + "?qrtext=" + encodeURIComponent(result);
                window.location.href = newLocation;

                // let newLocation2 = window.location.pathname + "?action=show&QrCodeText=" + result;
                // window.location.href = newLocation2;

                // document.getElementById('result').innerHTML = `<h2>Success!</h2> 
                // <p><a href="${result}">${result}</a></p>
                // `;
                
                scanner.clear();
                document.getElementById('reader').remove();

            }

            function error(err) {
                console.error(err);
            }

        </script>
        <br>
            </div>
        </div>
    <div class="TransactHistory">
        <input type="button" value="Back" onclick="window.location.href='AdminTransactionRecord.php'">
    </div>
    <div class="title">
        Transaction Record History
    </div>
    <div class="table-container">    
        <table class="Table">
                <tr>
                    <th>No.</th>
                    <th></th>
                    <th>Date and Time</th>
                    <th>Package ID</th>
                    <th>Package Name</th>
                    <th>Package Type</th>
                    <th>Client's Name</th>
                    <th>Client's Number</th>
                    <th>Client's Email</th>
                    <th>Status Review</th>
                    <th>Payment Confirmation</th>
                    <th>Package Status</th>
                    <th>Payment Method</th>
                    
                    <!-- <th></th>
                    <th></th> -->
                </tr>
            <?php
          
            $conn = mysqli_connect("localhost", "root", "", "transaction");
          
            if ($conn->connect_error) {
                die('Connection failed: ' . $conn->connect_error);
            }

            $sql = "SELECT * FROM client_package WHERE Payment_Confirmation = 'Accept'";
            $result = $conn->query($sql);
          

            if ($result->num_rows > 0) {
                $counter=1;
                while ($row = $Search->fetch_assoc())  {
                    $transaction_Id = $row["id"];
                    //$uniqueID = "popup_" . $row['id'];
                    //$buttonID = "accept_" . $row['id'];
                    $client_id = $row["Client_ID"];
                    $sqlDetails3 = "SELECT * FROM account WHERE id = ?";
                    $stmt3 = $conn->prepare($sqlDetails3);
                    $stmt3->bind_param('i', $client_id);
                    $stmt3->execute();
                    $detailsResult3 = $stmt3->get_result();
                    $Profile = $detailsResult3->fetch_assoc();
            ?>
        <div id="onlinePayment" style="display: none;">
                      <h3>Online Payment</h3>
          
                <tr >
                    <td><?php echo $counter++; ?></td> 
                    <td><a href="?id=<?php echo $row['id'];?>&action=show" class="Show">Details</a></td>
                    <td><?php echo $row["Date_Time"]; ?></td>
                    <td><?php echo strtoupper($row["PackageNameCode"]); ?></td>
                    <td><?php echo $row["Package_Name"]; ?></td>
                    <td><?php echo $row["Package_Type"]; ?></td>

                    <td><?php echo $Profile["Firstname"]; ?> <?php echo $Profile["Lastname"]; ?></td>
                    <td><?php echo $Profile["Phone_Num"];?></td>
                    <td><?php echo $Profile["Email"];?></td>

                    <td><?php echo $row["Status_Review"]; ?></td>
                    <td><?php echo $row["Payment_Confirmation"]; ?></td>
                    <td><?php echo $row["Arriving_Package"]; ?></td>
                    <td><?php echo $row["Payment_Method"]; ?></td>
                    

                    
                </tr>

                </div>
                <?php
            
        }
        } else {
            echo "No Record.";
        }
        ?>
        </table>
    </div>
</div>

    <?php 
        if (isset($_GET['action']) && $_GET['action'] == 'show' && isset($_GET['id'])):
          
            $sqlDetails = "SELECT * FROM client_package WHERE id=? ";
            $stmt = $conn->prepare($sqlDetails);
            $stmt->bind_param('i', $_GET['id']);
            $stmt->execute();
            $detailsResult = $stmt->get_result();
            $detailsRow = $detailsResult->fetch_assoc();

            $image = $_GET['id'];
            
            $sqlDetails2 = "SELECT * FROM package_image WHERE Package_ID = ?";
            $stmt2 = $conn->prepare($sqlDetails2);
            $stmt2->bind_param('i', $image);
            $stmt2->execute();
            $detailsResult2 = $stmt2->get_result();
            
    ?>  
    <div class="Show_Popup" >
    <div class="add_form">
    <form action="PHP_file/AdminInventoryPackage_PHP.php" method="POST">
        
                <span class="Iconclose">
                <img src="icons/icons8-go-back-50.png" class="Icon-close"alt="">
            </span>
            <script>
                        document.addEventListener("DOMContentLoaded", function() {
                        document.querySelectorAll(".Iconclose").forEach(function(button) {
                        button.addEventListener("click", function(event) {
                        event.preventDefault();
                        document.querySelector(".Show_Popup").style.display = "none";
                        
                    
                        var url = window.location.toString();
                        if (url.indexOf("?") > 0) {
                                var clean_url = url.substring(0, url.indexOf("?"));
                                window.history.replaceState({}, document.title, clean_url);
                            }
                        });
                    });
                    });
                    </script>
                    
                <div class="title_Review">
                        Package Information
                        </div>

    <div class="package_profile">
    <div class="imagePreview">
                <div class="slides">
                <?php
                while ($imageRow = mysqli_fetch_assoc($detailsResult2)) {
                    $baseImagePath = 'package_Image/';
                    $packageImage = $baseImagePath . $imageRow["Package_Image"];
                    if(file_exists($packageImage)){
                        echo "<div class='slide' style='background-image: url(\"{$packageImage}\");'></div>";
                    }else{
                        echo "Image not found: " . $packageImage;
                    }
                }
                ?>
                </div>
                <span class="arrow left">◀</span>  
                <span class="arrow right">▶</span>
            </div>
            <script>
                document.querySelectorAll('.imagePreview').forEach((container) => {
                    let slideIndex = 0;
                    const slides = container.querySelector('.slides');
                    const leftArrow = container.querySelector('.arrow.left');
                    const rightArrow = container.querySelector('.arrow.right');

                    if (slides.children.length > 0) {
                        slides.children[0].style.display = 'block';
                    }
                    if (slides.children.length > 1) {
                        leftArrow.style.display = 'block';
                        rightArrow.style.display = 'block';
                    } else {
                        leftArrow.style.display = 'none';
                        rightArrow.style.display = 'none';
                    }

                    leftArrow.addEventListener('click', function() {
                        slides.children[slideIndex].style.display = 'none';
                        slideIndex = (slideIndex - 1 + slides.children.length) % slides.children.length;  // Previous image
                        slides.children[slideIndex].style.display = 'block';
                    });

                    rightArrow.addEventListener('click', function() {
                        slides.children[slideIndex].style.display = 'none';
                        slideIndex = (slideIndex + 1) % slides.children.length;  // Next image
                        slides.children[slideIndex].style.display = 'block';
                    });
                });
            </script>
            
            <div class="PackageInfo">
                <div class="desc1">
                        <h3>Package Information</h3>
                </div>
                <br>
                    <p><b>Date and Time:</b> <?php echo $detailsRow["Date_Time"]; ?></p>
                    <p><b>Package ID:</b>  <?php echo strtoupper($detailsRow["PackageNameCode"]); ?></p>
                    <p><b>Package Name:</b>  <?php echo $detailsRow["Package_Name"]; ?></p>
                    <p><b>Package Type:</b>  <?php echo $detailsRow["Package_Type"]; ?></p>
                    <p><b>Package Weight:</b>  <?php echo $detailsRow["Package_Weight"]; ?></p>
                    <p> <b>Package Quantity:</b>  <?php echo $detailsRow["Package_Quantity"]; ?></p>
                        
                        </div>
            <div class="Personal_Info1">
                <br>
                    <div class="desc2">
                            <h3 >Personal Information</h3>
                    </div>
                <br>
                    <p><b>Client's Name: </b> <?php echo $Profile["Firstname"]; ?> <?php echo $Profile["Lastname"]; ?></p>
                    <p><b>Client's Phone Number:</b> <?php echo $Profile["Phone_Num"]; ?></p>
                    <p><b>Client's Email:</b> <?php echo $Profile["Email"]; ?></p>
                <br>
                    <p><b>Receiver's Name:</b>  <?php echo $detailsRow["Receiver_Name"]; ?></p>
                    <p><b>Receiver's Phone Number:</b>  <?php echo $detailsRow["Receiver_PhoneNum"]; ?></p>
                    <p><b>Receiver's Email:</b>  <?php echo $detailsRow["Receiver_Email"]; ?></p>
                    <p><b>Receiver's Address:</b>  <?php echo $detailsRow["Receiver_Address"]; ?></p>
            </div>

                <div class="PaymentMethod">
                <div class="desc1">
                        <h3>Terms of Payment</h3>
                </div>
                <br>
                    <p><b>Payment Method: </b> <span style="color: red;"><?php echo $detailsRow["Payment_Method"]; ?> </span></p>
                    <?php
                        if ($detailsRow['Payment_Confirmation'] == 'Accept') {
                            $stmt = $conn->prepare("SELECT * FROM payment_transaction WHERE Package_ID= ?");
                            $stmt->bind_param("s", $detailsRow["id"]);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $payment = $result->fetch_assoc();

                            $stmt2 = $conn->prepare("SELECT * FROM package_transaction WHERE Package_id= ?");
                            $stmt2->bind_param("s", $detailsRow["id"]);
                            $stmt2->execute();
                            $result2 = $stmt2->get_result();
                            $TransactionRecord = $result2->fetch_assoc();
                                
                        ?>
                        <input type="hidden" name="TransactionPaymentID" value="<?php echo $payment["Package_ID"]; ?>">
                        <p><b>Total Payment Price: </b> <span  style="color: red;">₱ <?php echo number_format($payment["Price_Payment"], 2); ?> </span></p>
                        <!-- <p><b>Payment Type: </b> <span style="color: red;"><?php echo !empty($payment["Payment_Type"]) ? $payment["Payment_Type"] : 'On-Site'; ?></span></p> -->
                        <p><b>Reference No. : </b> <span style="color: red;"><?php echo !empty($payment["OR_No"]) ? $payment["OR_No"] : 'Empty'; ?></span></p>
                        <p><b>Payment Confirmation: </b> <span style="color: red;"> Payment has been Received </span></p>
                        <?php 
                           if ($detailsRow['Payment_Method'] == 'Online Payment') {
                         ?>
                            <p><b>Reference Screenshot: </b> <input type="button" value="View Images" class="imagetransac"></p>
                                    <script>
                                    document.querySelector(".imagetransac").addEventListener("click", function() {
                                    document.querySelector(".Show_PopupImg").style.display = "flex";
                                    });

                                    // document.querySelector(".close").addEventListener("click", function() {
                                    // document.querySelector(".Decline_Popup").style.display = "none";
                                    // });

                                    </script>
                         <?php 
                          }
                         ?>


                        <br>
                        <div class="desc1">
                        <h3>Package Status</h3>
                        </div>
                        <br>
                        
                        <p><b>Name Of Staff: </b> <span  style="color: red;"> <?php echo $TransactionRecord["Staff_Name"]; ?></span></p>
                        <p><b>Truck Plate Number: </b> <span style="color: red;"> <?php echo $TransactionRecord["Truck_PlateNumber"]; ?> </span></p>
                        <p><b>On-Going: </b> <span style="color: red;"> <?php echo $TransactionRecord["On_Going"]; ?> </span></p>
                        <p><b>Arrived At Masbate: </b> <span style="color: red;"> <?php echo $TransactionRecord["Arrive_Masbate"]; ?> </span></p>
                        <p><b>Package Received: </b> <span style="color: red;"> <?php echo $TransactionRecord["Delivered"]; ?> </span></p>
                        <p><b>Proof of Delivery: </b></p>
                        <div class="ImageTransaction">
                                        <?php
                                       
                                        $stmtImages = $conn->prepare("SELECT Proof_Received FROM package_transaction WHERE Package_id = ?");
                                        $stmtImages->bind_param("s", $detailsRow["id"]);
                                        $stmtImages->execute();
                                        $resultImages = $stmtImages->get_result();

                                        $baseImagePath = 'ProofOfPackage/'; 

                                        while ($imageRow = $resultImages->fetch_assoc()) {
                                            $relativePath = trim($imageRow["Proof_Received"]);
                                            $packageImage = $baseImagePath . $relativePath;

                                            $absolutePath = __DIR__ . '/' . $packageImage;

                                            if (file_exists($absolutePath)) {
                                                echo "<div class='slide' style='background-image: url(\"{$packageImage}\");'></div>";
                                            } else {
                                                echo "Image not found: " . $absolutePath;
                                            }
                                        }
                                        ?>
                                    </div>
                            
                              <?php } //}  ?>
            </div>
        </div>
                
                    <div class="Personal_Info2">
                            <br>
                            <div class="desc2">
                                    <h3 >Personal Information</h3>
                            </div>
                            <br>
                            <p><b>Client's Name: </b> <?php echo $Profile["Firstname"]; ?> <?php echo $Profile["Lastname"]; ?></p>
                            <p><b>Client's Phone Number:</b> <?php echo $Profile["Phone_Num"]; ?></p>
                            <p><b>Client's Email:</b> <?php echo $Profile["Email"]; ?></p>
                            <br>
                            <p><b>Receiver's Name:</b>  <?php echo $detailsRow["Receiver_Name"]; ?></p>
                            <p><b>Receiver's Phone Number:</b>  <?php echo $detailsRow["Receiver_PhoneNum"]; ?></p>
                            <p><b>Receiver's Email:</b>  <?php echo $detailsRow["Receiver_Email"]; ?></p>
                            <p><b>Receiver's Address:</b>  <?php echo $detailsRow["Receiver_Address"]; ?></p>
                    </div>
                    <!-- <div class="button">
                            
                                <input type="hidden" name="id" value="<?php echo $detailsRow["id"];?>"/>
                                <input type="hidden" name="ArrivingPackage" value="On-Site"/>
                                <span class="Submit_span">Please check the Package First Before Confirm</span> <br>
                            <input type="submit" name="Package_Update" id="submit" class="submit_btn" value="Confirm Package"  onclick="printContent()">

                            <?php if($detailsRow["Arriving_Package"] == 'On-Site'){
                                ?>
                                <script>
                                     document.querySelector(".Submit_span").style.display = "none";
                                   document.querySelector(".submit_btn").style.display = "none";
                                </script>
                                <h2 style="color: red;"><b> The Package has been On-Site Storage</b></h2>

                        <?php
                        } ?>
                            </div> -->
                            
                      </form>      
                </div>
            </div>
    <div class="Show_PopupImg" >
        <div class="add_form">
        <span class="Icon_close" id="icon_Close">
                <img src="icons/icons8-go-back-50.png" class="Icon-close"alt="">
            </span>
            <script>
                  document.querySelector(".Icon_close").addEventListener("click", function() {
                        document.querySelector(".Show_PopupImg").style.display = "none";
                        });
            </script>
       
            <div class="title_Review">
                Receipt Image
            </div>
            <div class="table-container">
        <table class="Table">
            <tr>
                <th>No.</th>
                <th>Receipt Images</th>
                <th>Status </th>
         
            </tr>
        <?php
        // Database connection
        $packageid = $_GET['id'];
        $sql2 = "SELECT * FROM payment_screenshots WHERE Package_id = $packageid AND Status_image IN ('Accept', 'Pending')";
        $result2 = $conn->query($sql2);
        
        if ($result2->num_rows > 0) {
            $counter=1;
            while ($row1 = $result2->fetch_assoc())  {
                

               
        ?>
             <form action="PHP_file/AdminPaymentTransaction_Decline.php" method="POST">
    <!-- <div id="onlinePayment" style="display: none;">
                  <h3>Online Payment</h3> -->
                  <input type="hidden" name="package_id" value="<?php echo  $packageid;?>"/>
                  <input type="hidden" name="Image_id" value="<?php echo $row1["id"];?>"/>
            <tr >
                <td><?php echo $counter++; ?></td>
                <td><img src="Payment_Screenshots/<?php echo $row1["Screenshot_Payment"]; ?>" alt="Payment Screenshot"></td>
                <td><?php echo $row1["Status_image"]; ?></td>
                

            </tr>
            </form>
            <!-- </div> -->
                        <?php

                            }
                            } else {
                                // echo "No Record.";
                            }
                        ?>
                </table>
            </div>
        
        </div>
    </div>
            <?php endif; ?>
</body>
</html>
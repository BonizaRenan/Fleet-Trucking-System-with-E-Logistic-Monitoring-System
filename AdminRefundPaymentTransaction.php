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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="AdminRefundPaymentTransaction.css">
    <title>Refund Payment</title>
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

        <div class="PaymentSelection">
                <!-- <label>Select payment method: </label> -->
                <select id="paymentMethod" onchange="filterPaymentMethod()">
                    <option value="" selected disabled>Select payment method</option>
                    <option value="All">All</option>
                    <option value="online">Online Payment</option>
                    <option value="onsite">On-Site Payment</option>
                </select>
        </div>

        <script>
            function filterPaymentMethod() {
            const searchQuery = document.getElementById('search_input').value;
            const selectedMethod = document.getElementById('paymentMethod').value;

            let newLocation = window.location.pathname;

            if (searchQuery) {
                newLocation += '?search=' + encodeURIComponent(searchQuery);
                if (selectedMethod) {
                    newLocation += '&paymentMethod=' + encodeURIComponent(selectedMethod);
                }
            } else if (selectedMethod) {
                newLocation += '?paymentMethod=' + encodeURIComponent(selectedMethod);
            }
            window.location.href = newLocation;
        }
    </script>
        

    <?php
      // Database connection at the top of the page (only once)
        $conn = mysqli_connect("localhost", "root", "", "transaction");
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        $sql = "SELECT * FROM payment_transaction 
        JOIN client_package ON payment_transaction.Package_ID = client_package.id 
        WHERE payment_transaction.Refund_Confirmation = 'Refund'";

        if (isset($_GET['paymentMethod']) && $_GET['paymentMethod'] == 'All') {
    
            if (isset($_GET['search'])) {
                $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);
                $sql .= " AND (client_package.Package_Name LIKE '%$searchTerm%' OR client_package.PackageNameCode LIKE '%$searchTerm%' OR client_package.Client_name LIKE '%$searchTerm%' OR client_package.Receiver_Name LIKE '%$searchTerm%' OR client_package.Payment_Method LIKE '%$searchTerm%' OR client_package.Package_name LIKE '%$searchTerm%')";
            }
        } else {
            if (isset($_GET['search'])) {
                $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);
                $sql .= " AND (client_package.Package_Name LIKE '%$searchTerm%' OR client_package.Client_name LIKE '%$searchTerm%' OR client_package.PackageNameCode LIKE '%$searchTerm%' OR client_package.Receiver_Name LIKE '%$searchTerm%' OR client_package.Payment_Method LIKE '%$searchTerm%' OR client_package.Package_Type LIKE '%$searchTerm%')";
            }
        
            if (isset($_GET['paymentMethod'])) {
                $paymentMethod = mysqli_real_escape_string($conn, $_GET['paymentMethod']);
                if ($paymentMethod == 'online') {
                    $sql .= " AND Payment_Method = 'Online Payment'";
                } elseif ($paymentMethod == 'onsite') {
                    $sql .= " AND Payment_Method = 'On-Site Payment'";
                }
            }
        }


        $result = $conn->query($sql);
    ?>

         <!-- Search bar End -->

         <div class="box">

<div class="Payment_BTN">
        <input type="button" value="Back" onclick="window.location.href='AdminPaymentTransaction.php'">
    </div>

<div class="title">
Refund Payment
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
                    <th>Receiver's Name</th>
                    <th>Receiver's Number</th>
                    <th>Receiver's Email</th>
                    <th>Payment Method</th>
                    
                    <!-- <th></th>
                    <th></th> -->
                </tr>
            <?php
            // Database connection
            $conn = mysqli_connect("localhost", "root", "", "transaction");
        
            if ($conn->connect_error) {
                die('Connection failed: ' . $conn->connect_error);
            }


            $result = $conn->query($sql);
        

            if ($result->num_rows > 0) {
                $counter=1;
                while ($row = $result->fetch_assoc())  {
               
                        $sqlDetails = "SELECT * FROM client_package WHERE id=? ";
                        $stmt = $conn->prepare($sqlDetails);
                        $stmt->bind_param('i', $row["Package_ID"]);
                        $stmt->execute();
                        $detailsResult = $stmt->get_result();
                        $detailsRowtable = $detailsResult->fetch_assoc();


                    $transaction_Id = $row["Package_ID"];
                    //$uniqueID = "popup_" . $row['id'];
                    //$buttonID = "accept_" . $row['id'];
                    $client_id = $detailsRowtable["Client_ID"];
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
                    <td><a href="?id=<?php echo $detailsRowtable['id'];?>&action=show" class="Show">Details</a></td>
                    <td><?php echo $detailsRowtable["Date_Time"]; ?></td>
                    <td><?php echo strtoupper($detailsRowtable["PackageNameCode"]); ?></td>
                    <td><?php echo $detailsRowtable["Package_Name"]; ?></td>
                    <td><?php echo $detailsRowtable["Package_Type"]; ?></td>
                    
                    <td><?php echo $Profile["Firstname"]; ?> <?php echo $Profile["Lastname"]; ?></td>
                    <td><?php echo $Profile["Phone_Num"];?></td>
                    <td><?php echo $Profile["Email"];?></td>

                    <td><?php echo $detailsRowtable["Receiver_Name"]; ?></td>
                    <td><?php echo $detailsRowtable["Receiver_PhoneNum"]; ?></td>
                    <td><?php echo $detailsRowtable["Receiver_Email"]; ?></td>
                    <td><?php echo $detailsRowtable["Payment_Method"]; ?></td>
                    

                    
                </tr>

                </div>
                <?php
            
        }
        } else {
            //echo "No Record.";
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
                        slideIndex = (slideIndex - 1 + slides.children.length) % slides.children.length; 
                        slides.children[slideIndex].style.display = 'block';
                    });

                    rightArrow.addEventListener('click', function() {
                        slides.children[slideIndex].style.display = 'none';
                        slideIndex = (slideIndex + 1) % slides.children.length; 
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
                    <p><b>Payment Method: </b> <span style="color: red;"><?php echo $detailsRow["Payment_Method"]; ?></span></p>
                    <?php
                        if ($detailsRow['Status_Review'] == 'Accept') {
                            $stmt = $conn->prepare("SELECT * FROM payment_transaction WHERE Package_ID= ?");
                            $stmt->bind_param("s", $detailsRow["id"]);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $payment = $result->fetch_assoc();         
                        ?>
                        <p><b>Total Payment Price: </b> <span style="color: red;">₱ <?php echo number_format($payment["Price_Payment"], 2); ?> </span></p>
                        <p><b>Payment Comfirmation: </b><span style="color: red;">Paid </span></p>
                        <p><b>Reference No. </b><span style="color: red;"><?php echo $payment["OR_No"];?> </span></p>
                        <br>
                        <div class="desc1">
                            <h3>Refund of Payment</h3>
                        </div>
                        <br>
                        <p><b>Reason of Refund: </b><br><span style="overflow-wrap: break-word; font-size:15px; color:red;"><?php echo $payment["Refund_Reason"];?> </span></p>
                            <?php 
                                if ($detailsRow['Payment_Method'] == 'Online Payment') {

                            ?>
                        <p><b>Refund Type: </b><span style="color:red;"><?php echo $payment["Refund_Type"];?> </span></p>
                        <p><b>Refund QrCode: </b><span style="color:red;"> </span></p>
                        <div class="ImageTransaction">
                                <?php
                            
                                $stmtImages = $conn->prepare("SELECT Refund_Image FROM payment_transaction WHERE Package_ID = ?");
                                $stmtImages->bind_param("s", $detailsRow["id"]);
                                $stmtImages->execute();
                                $resultImages = $stmtImages->get_result();

                                $baseImagePath = 'Payment_Screenshots/'; 

                                while ($imageRow = $resultImages->fetch_assoc()) {
                                    $relativePath = trim($imageRow["Refund_Image"]);
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
                           
                        <?php } ?>

                        <?php 
                                if ($detailsRow['Payment_Method'] == 'On-Site Payment') {
                            ?>
                                    <p><b>Refund Type: </b><span style="color:red;"> On Site Refund </span></p>
                                    <script>
                                 document.addEventListener("DOMContentLoaded", function() {
                                      document.querySelector(".button").style.marginTop = "-10px";
                                    });
                            </script>
                            <?php 
                                }
                            ?>

                        <input type="hidden" name="TransactionPaymentID" value="<?php echo $payment["Package_ID"]; ?>">

                            <?php } ?>
                         
                                
                                
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
                    
                    <div class="button">
                            
                                <!-- <input type="hidden" name="id" value="<?php echo $detailsRow["id"];?>"/>
                                <input type="hidden" name="paymentConfirmation" value="Accept"/>
                                <input type="hidden" name="ArrivingPackage" value="Pending"/> -->
                            <input type="submit" name="ConfirmationPayment" id="submit" class="EditPayment" value="Refund Payment">
                            
                            <script>
                                        document.querySelector(".EditPayment").addEventListener("click", function() {
                                        document.querySelector(".Refund_Popup").style.display = "flex";
                                          });

                                    </script>

                        <!-- <script>
                            document.querySelector(".EditPayment").addEventListener("click", function() {
                            document.querySelector(".Price_Popup").style.display = "flex";
                            });
                        
                            document.querySelector(".Icon-close").addEventListener("click", function() {
                            document.querySelector(".Price_Popup").style.display = "none";
                            });
                        </script> -->
                            </div>
                            
                </div>
            </div>
            
<div class="Refund_Popup">
        <div class="Refund_form">
            <form action="PHP_file/AdminRefundTransaction_PHP.php" method="POST" enctype="multipart/form-data">
                <span class="IconclosePayment">
                    <img src="icons/icons8-go-back-50.png" class="iconcloseRefund"alt="">
                </span>

                    <script>
                       document.querySelector(".iconcloseRefund").addEventListener("click", function() {
                                document.querySelector(".Refund_Popup").style.display = "none";
                                });
                    </script>

                <div class="title_Refund">
                    Refund Payment
                </div>
                <?php 
                       if (isset($detailsRow['Payment_Method']) && $detailsRow['Payment_Method']== 'Online Payment') {
                ?>
            <input type="hidden" name="id" value="<?php echo $detailsRow['id'] ?>">


            <!-- Image Upload Section in Vehicle Registration Popup -->
            <!-- <span style="margin-left:35%;">Upload your QR Scan</span> -->
            <div class="uploadimage_Refund">
                <div class="imagePreview_Refund">
                    <?php
                    echo "<img class='defaultImage' src='logo/OTS Logo.png' alt='Default Image'>";
                    ?>
                </div>
                <div class="file_btn">
                    <!-- <button id="fileButton" class="filebutton">Upload Image</button> -->
                    <input type="file" name="imageFile" id="imageFile" class="imageFile" accept="image/*" multiple required><br>
                    <span style="margin-left:20%;">Upload your Reference No.</span>
                </div>
                <script>
                 document.addEventListener("DOMContentLoaded", function() {
                    const imageFileInput = document.getElementById('imageFile');
                
                    imageFileInput.addEventListener('change', function(event) {
                        const previewDiv = document.querySelector('.imagePreview_Refund');
                        previewDiv.innerHTML = ''; 
                    
                        for (let i = 0; i < event.target.files.length; i++) {
                            const file = event.target.files[i];
                            
                            if (file) {
                                const img = document.createElement('img');
                                img.src = URL.createObjectURL(file);
                                img.onload = function() {
                                    URL.revokeObjectURL(img.src); 
                                }
                                
                                previewDiv.appendChild(img);
                            }
                        }
                    
                    
                        if (event.target.files.length === 0) {
                            const defaultImg = document.createElement('img');
                            defaultImg.src = 'logo/OTS Logo.png';
                            defaultImg.className = 'defaultImage';
                            defaultImg.alt = 'Default Image';
                            previewDiv.appendChild(defaultImg);
                        }
                    });
                });
                </script>
            </div>
               
                                    
                 
            <?php 
            }   if ($detailsRow['Payment_Method'] == 'On-Site Payment') {
            ?>
                <input type="hidden" name="id" value="<?php echo $detailsRow['id'] ?>">
                <div class="input-box">
                                    <span class="details">Confirmation Payment:</span>
                                    <input type="text" name="ReferenceNo" id="ReferenceNo" placeholder="Please Type 'Confirm' if the Customer pay." pattern="Confirm" title="You must type 'Confirm' here." required oninput="validateInput(this)">
                                        <script>function validateInput(inputElement) {
                                            if (inputElement.value !== "Confirm") {
                                                inputElement.setCustomValidity("You must type 'Confirm' here.");
                                            } else {
                                                inputElement.setCustomValidity(""); 
                                            }
                                        }
                                        </script>

                           
                </div> 

            <?php 
            }
            ?>
                                <!--...your payment form fields...-->
                <div class="button">
                    <input type="submit" name="submit_Refund" id="submit" value="Submit">
                </div>
            </form>
        </div>
    </div>
          

            <?php endif; ?>

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
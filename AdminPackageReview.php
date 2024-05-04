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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="AdminPackageReview.css">
    <title>Package Review</title>
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
          
            $conn = mysqli_connect("localhost", "root", "", "transaction");
            $sql = "SELECT * FROM client_package WHERE Status_Review = 'Pending'";

          
            if (isset($_GET['paymentMethod']) && $_GET['paymentMethod'] == 'All') {
             

                if (isset($_GET['search'])) {
                    $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);
                    $sql .= " AND (Package_Name LIKE '%$searchTerm%' OR Client_name LIKE '%$searchTerm%' OR Receiver_Name LIKE '%$searchTerm%' OR Payment_Method LIKE '%$searchTerm%' OR Package_Type LIKE '%$searchTerm%')";
                }

            } else {
            
                if (isset($_GET['search'])) {
                    $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);
                    $sql .= " AND (Package_Name LIKE '%$searchTerm%' OR Client_name LIKE '%$searchTerm%' OR Receiver_Name LIKE '%$searchTerm%' OR PackageNameCode LIKE '%$searchTerm%' OR Payment_Method LIKE '%$searchTerm%' OR Package_Type LIKE '%$searchTerm%')";
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

            $Search = $conn->query($sql);
        ?>
            
 
    <div class="box">

        <div class="title">
        Package Review
        </div>
        <div class="table-container"> 
        <table class="Table">
                <tr>
                    <th>No.</th>
                    <th></th>
                    <!-- <th></th>
                    <th></th> -->
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

                </tr>
            <?php
            // Database connection
            $conn = mysqli_connect("localhost", "root", "", "transaction");

            if ($conn->connect_error) {
                die('Connection failed: ' . $conn->connect_error);
            }
        
            $sql = "SELECT * FROM client_package WHERE Status_Review = 'Pending'";
            $result = $conn->query($sql);


        
            if ($result->num_rows > 0) {
                $counter=1;
                while ($row = $Search->fetch_assoc()) {
                    $transaction_Id = $row["id"];
                
                    $client_id = $row["Client_ID"];
                    $sqlDetails3 = "SELECT * FROM account WHERE id = ?";
                    $stmt3 = $conn->prepare($sqlDetails3);
                    $stmt3->bind_param('i', $client_id);
                    $stmt3->execute();
                    $detailsResult3 = $stmt3->get_result();
                    $Profile = $detailsResult3->fetch_assoc();
                

                    //$uniqueID = "popup_" . $row['id'];
                    //$buttonID = "accept_" . $row['id'];
                
            ?>
                <tr >
                    <td><?php echo $counter++; ?></td> 
                    <td><a href="?id=<?php echo $row['id'];?>&action=show" class="Show">Details</a></td>
                    <!-- <td><a href="?id=<?php echo $row['id'];?>&action=Accept" class="accept">Accept</a></td>
                    <td><a href="?id=<?php echo $row['id'];?>&action=decline" class="decline">Decline</td> -->
                    <td><?php echo $row["Date_Time"]; ?></td>
                    <td><?php echo strtoupper($row["PackageNameCode"]); ?></td>
                    <td><?php echo $row["Package_Name"]; ?></td>
                    <td><?php echo $row["Package_Type"]; ?></td>
                
                    <td><?php echo $Profile["Firstname"]; ?> <?php echo $Profile["Lastname"]; ?></td>
                    <td><?php echo $Profile["Phone_Num"];?></td>
                    <td><?php echo $Profile["Email"];?></td>

                    <td><?php echo $row["Receiver_Name"]; ?></td>
                    <td><?php echo $row["Receiver_PhoneNum"]; ?></td>
                    <td><?php echo $row["Receiver_Email"]; ?></td>
                    <td><?php echo $row["Payment_Method"]; ?></td>

                

                </tr>
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
        
            <div class="PaymentMethod">
                <div class="desc1">
                    <h3>Terms of Payment</h3>
                </div>
                <br>
                <p><b>Payment Method: </b><span style="color:red;"><?php echo $detailsRow["Payment_Method"]; ?></span></p>
                <p><b>Estimated Price: </b><span style="color:red;">₱ <?php echo number_format($detailsRow["DeclaredValuePrice"], 2); ?></span></p>
            </div>
    </div>
                
        <div class="Personal_Info">
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

        <div class="Review_button">

            <input type="button"  id="Accept" class="review_accept" value="Accept Package">
            <input type="button"  id="Decline" class="review_decline" value="Decline Package">
        </div>

        <script>
        document.querySelector(".review_accept").addEventListener("click", function() {
        document.querySelector(".Price_Popup").style.display = "flex";
        });

        document.querySelector(".review_decline").addEventListener("click", function() {
        document.querySelector(".Decline_Popup").style.display = "flex";
        });

        // document.querySelector(".close").addEventListener("click", function() {
        // document.querySelector(".Decline_Popup").style.display = "none";
        // });

    </script>

    </div>
</div>
<?php //endif; ?>

<?php 
    // if (isset($_GET['action']) && $_GET['action'] == 'Accept' && isset($_GET['id'])): 
        $id = $detailsRow["id"];
?>
                
    <div class="Price_Popup" id="<?php // echo $uniqueID; ?>">
        <div class="price_form">
            <form action="PHP_file/AdminPackageReview_Payment.php" method="POST">
                <span class="IconclosePayment">
                    <img src="icons/icons8-go-back-50.png" class="Icon-close"alt="">
                </span>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                        document.querySelectorAll(".IconclosePayment").forEach(function(button) {
                        button.addEventListener("click", function(event) {
                        event.preventDefault();
                        document.querySelector(".Price_Popup").style.display = "none";
                        
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

                <div class="title_price">
                    Payment Price
                </div>

                <div class="input-box">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                 
                    
                    <input type="hidden" name="Client_PaymentConfirmation" value="Pending">
                    <input type="hidden" name="Decision" value="Accept" required>
                    <input type="hidden" name="clientDecision" value="Confirm" required>

                        <span class="details">Input Payment Price</span>
                        <input type="text" name="PaymentPrice1" id="PaymentPrice" placeholder="Enter a Price to Pay" required>
                        <input type="hidden" name="PaymentPrice" id="PaymentPricewithoutpesosign" placeholder="Enter a Price to Pay" required>

                        <script>
                            const priceInput = document.getElementById('PaymentPrice');
                            const priceInputWithoutPesoSign = document.getElementById('PaymentPricewithoutpesosign');

                            priceInput.addEventListener('input', function () {
                                const priceValue = priceInput.value;

                                if (priceValue.match(/^\d{0,8}(\.\d{0,2})?$|^₱\d{0,8}(\.\d{0,2})?$/)) {
                                    priceInput.setCustomValidity('');
                                    updateWithoutPesoSignInput(priceValue);
                                } else {
                                    priceInput.setCustomValidity('Please enter a valid price (up to 8 digits, and up to 2 decimal places).');
                                }
                            });

                            priceInput.addEventListener('blur', function () {
                                const sanitizedValue = priceInput.value.replace(/[^\d.₱]/g, '');
                                priceInput.value = sanitizedValue.includes('.')
                                    ? (sanitizedValue.startsWith('₱') ? '' : '₱') + sanitizedValue
                                    : sanitizedValue.length > 0
                                        ? (sanitizedValue.startsWith('₱') ? '' : '₱') + `${sanitizedValue}.00`
                                        : '';

                                updateWithoutPesoSignInput(priceInput.value);
                            });

                            function updateWithoutPesoSignInput(value) {
                                priceInputWithoutPesoSign.value = value.replace('₱', '');
                            }
                        </script>

                </div> 

                <div class="input-box">
                        <span class="details">Provide the reason for the payment price</span>
                        <input type="text" name="PaymentPriceDesc" id="PaymentPriceDesc" placeholder="E.g. Fragile" required>
                           
                </div> 
                                <!--...your payment form fields...-->
                <div class="button">
                    <input type="submit" name="submit_payment" id="submit" value="Submit">
                </div>
            </form>
        </div>
    </div>


<?php 
    // if (isset($_GET['action']) && $_GET['action'] == 'decline' && isset($_GET['id'])): 
        $id = $detailsRow["id"];
        $Decline = "SELECT * FROM client_package WHERE id=? ";
        $stmt = $conn->prepare($Decline);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $DeclineResult = $stmt->get_result();
        $Declinerow = $DeclineResult->fetch_assoc(); 
    ?>
    <div class="Decline_Popup">
        <div class="Decline_case"> 
            <form action="PHP_file/AdminPackageReview_Decline.php" method="post">         
                <div class="title_Decline">
                    Decline Confirmation
                </div>

                <!-- <div class="descript_Decline">
                    Are you sure want to Decline this Transaction for <b><?php echo  $Declinerow['Client_name']; ?></b>?<br>
                </div> -->

                <div class="descript_Decline">
                    <span>Input a Decline Justification</span> <br>
                    <input type="text" name="Decline_Desc"  placeholder="Write the reason for the decline" required>
                </div>
               
                <div class="Decline_btn">
                        <input type="hidden" name="id" value="<?php echo $id ?>">
                        <input type="hidden" name="DeclineInput" value="Decline" >


                    <button type="button" class="close" >Close</button> 
                    <button type="submit" name="Decline_btn" class="Decline">Decline</button>
                </div>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                        document.querySelectorAll(".close").forEach(function(button) {
                        button.addEventListener("click", function(event) {
                        event.preventDefault();
                        document.querySelector(".Decline_Popup").style.display = "none";
                        
                     
                            // var url = window.location.toString();
                            //     if (url.indexOf("?") > 0) {
                            //             var clean_url = url.substring(0, url.indexOf("?"));
                            //             window.history.replaceState({}, document.title, clean_url);
                            //         }
                            });
                        });
                        });
                    </script>
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
<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "transaction");
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location: index.php');
    exit; 
}

if (isset($_GET['success_message'])) {
    $successMessage = htmlspecialchars($_GET['success_message']);
}

$sql = "SELECT Firstname, Lastname FROM account WHERE id = '$user_id'";
$result = $conn->query($sql);
$fetch = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="StaffPackageTransactionStatus.css">
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.0.3/html5-qrcode.min.js"></script>
    <title>Package Transaction Status</title>
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

        <div class="Search">
            <input type="text" class="Search_Input" id="search_input" placeholder="Search">
            <input type="button" class="Search_btn "value="Search">
        </div>
        <div class="back">
            <input type="button" class="Back "value="Back" onClick="location.href='StaffDashboard.php'">
        </div>
        <script>
            document.querySelector(".Search_btn").addEventListener("click", function() {
            const searchQuery = document.getElementById('search_input').value;
            window.location.href = window.location.pathname + '?search=' + encodeURIComponent(searchQuery);
            });
        </script>
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

        <?php
            
            $conn = mysqli_connect("localhost", "root", "", "transaction");
            $sql = "SELECT * FROM client_package WHERE Arriving_Package = 'On-Going'";

          
            if (isset($_GET['qrtext'])) {
                $qrtext = mysqli_real_escape_string($conn, $_GET['qrtext']);
                $sql .= " AND (	QrCodeText LIKE '%$qrtext%' )";
            }

                if (isset($_GET['search'])) {
                    $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);
                    $sql .= " AND (Package_Name LIKE '%$searchTerm%' OR PackageNameCode LIKE '%$searchTerm%' OR Client_name LIKE '%$searchTerm%' OR Receiver_Name LIKE '%$searchTerm%' OR Payment_Method LIKE '%$searchTerm%' OR Package_Type LIKE '%$searchTerm%')";
                }

            $Search = $conn->query($sql);
        ?>

         <!-- Search bar End -->

        
        <div class="title">
        Package Transaction Status
        </div>
        <div class="statusindicator">
            <div class="statusIndeicatortitle">Status Color:</div>
                <div class="statusindicator1">
                    <p class="ColorIndicator" style="color: Yellow;"> ■ </p><p class="nameIndicator">Pending Package Arrival</p>
                    <p class="ColorIndicator" style="color: Green;"> ■ </p><p class="nameIndicator">Pending Package Delivery</p>
                </div>
        </div>
    <div class="table-container">
        <table class="Table">
                <tr>
                    <th>No.</th>
                    <th>Status</th>
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
            // Database connection
            $conn = mysqli_connect("localhost", "root", "", "transaction");
          
            if ($conn->connect_error) {
                die('Connection failed: ' . $conn->connect_error);
            }
            $sql = "SELECT * FROM package_transaction WHERE Staff_Name = '" . $fetch['Firstname'] . " " . $fetch['Lastname'] . "'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
            $sql2 = "SELECT * FROM client_package WHERE Arriving_Package = 'On-Going'";
            $result2 = $conn->query($sql2);
            

            if ($result2->num_rows > 0) {
                $counter=1;
                while ($row = $Search->fetch_assoc())  {
                    $transaction_Id = $row["id"];
                    
                    $transaction = $conn->prepare("SELECT * FROM package_transaction WHERE Package_id = ?");
                    $transaction->bind_param("i", $transaction_Id);
                    $transaction->execute();

                    $transaction1 = $transaction->get_result();
                    $transactionDetails = $transaction1->fetch_assoc();

                    $Ongoing = isset($transactionDetails['On_Going']) ? $transactionDetails['On_Going'] : null ;
                    $arrival = isset($transactionDetails['Arrive_Masbate']) ? $transactionDetails['Arrive_Masbate'] : null;


                    $statusColor = 'white';
                    $statusName = 'None';
                   if($Ongoing == 'Confirm'){
                    $statusColor = 'Yellow';
                    $statusName = 'Pending Package Arrival';
                   }

                   if($arrival == 'Confirm'){
                    $statusColor = 'Green';
                    $statusName = 'Pending Package Delivery';
                   }

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
                    <td class="colorstatus" style="background-color: <?php echo $statusColor; ?>;"><p> <?php echo $statusName; ?></p></td>
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
        }} else {
            // echo "No Record.";
        } 
        ?>
        </table>
        </div>    
    </div>
    <?php 
        if (isset($_GET['action']) && $_GET['action'] == 'show' && isset($_GET['id'])):
            $qrCodeText = $_GET['id'];
          
            $sqlDetails = "SELECT * FROM client_package WHERE id=? ";
            // $sqlDetails = "SELECT * FROM client_package WHERE id=? ";
            $stmt = $conn->prepare($sqlDetails);
            $stmt->bind_param('s', $qrCodeText);
            $stmt->execute();
            $detailsResult = $stmt->get_result();
            $detailsRow = $detailsResult->fetch_assoc();

            $image = $detailsRow['id'];
            
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
                        <h3>Package Transaction Status </h3>
                </div>
                <br>
                    <p><b>Date and Time:</b> <?php echo $detailsRow["Date_Time"]; ?></p>
                    <p><b>Package Name:</b>  <?php echo strtoupper($detailsRow["PackageNameCode"]); ?></p>
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
                <p><b>Client Name: </b> <?php echo $Profile["Firstname"]; ?> <?php echo $Profile["Lastname"]; ?></p>
                <p><b>Client Phone Number:</b> <?php echo $Profile["Phone_Num"];?>  </p>
                <p><b>Client Email:</b> <?php echo $Profile["Email"];?></p>
                <br>
                <p><b>Receiver Name:</b>  <?php echo $detailsRow["Receiver_Name"]; ?></p>
                <p><b>Receiver Phone Number:</b>  <?php echo $detailsRow["Receiver_PhoneNum"]; ?></p>
                <p><b>Receiver Email</b>  <?php echo $detailsRow["Receiver_Email"]; ?></p>
                <p><b>Receiver Address</b>  <?php echo $detailsRow["Receiver_Address"]; ?></p>
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

                                // $stmt2 = $conn->prepare("SELECT * FROM client_package WHERE id= ?");
                                // $stmt2->bind_param("s", $detailsRow["id"]);
                                // $stmt2->execute();
                                // $result2 = $stmt2->get_result();
                                
                        ?>
                        <p><b>Total Payment Price: </b> <span  style="color: red;">₱ <?php echo number_format($payment["Price_Payment"], 2); ?> </span></p>
                        <!-- <p><b>Payment Type: </b> <span style="color: red;"><?php echo !empty($payment["Payment_Type"]) ? $payment["Payment_Type"] : 'On-Site'; ?></span></p> -->
                        <p><b>Reference No. : </b> <span style="color: red;"><?php echo !empty($payment["OR_No"]) ? $payment["OR_No"] : 'Empty'; ?></span></p>
                        <p><b>Payment Confirmation: </b> <span style="color: red;"> Payment has been Received </span></p>

                        <div class="desc1">
                        <h3>Package QR Code</h3>
                        </div>
                        <br>
                            <?php 
                            $qrTextFromDatabase = '';
                            if (isset($detailsRow['id'])) {
                                $stmt2 = $conn->prepare("SELECT QrCodeText FROM client_package WHERE id = ?");
                                $stmt2->bind_param('s', $detailsRow['id']);
                                $stmt2->execute();
                                $stmt2->store_result();
                                if ($stmt2->num_rows > 0) {
                                    $stmt2->bind_result($qrTextFromDatabase);
                                    $stmt2->fetch();
                                }
                                $stmt2->close();
                            }
                            
                            if ($qrTextFromDatabase) {?>
                            <div id="QrCodeGen"></div>
                            <p id="qrText">QRCode Text: <span></span></p>

                            <script>
                                var generatedCodes = [];
                                var qrCodeText = <?php echo json_encode($qrTextFromDatabase); ?>;
                                generatedCodes.push(qrCodeText);
                                var qrcode = new QRCode(document.getElementById("QrCodeGen"), {
                                text: qrCodeText,
                                width: 256,
                                height: 256,
                                colorDark: "#000000",
                                colorLight: "#ffffff",
                                correctLevel: QRCode.CorrectLevel.H
                            });

                         
                            document.querySelector("#qrText span").textContent = qrCodeText;
                        </script>
                            
                              <?php }}  ?>
                        </div>
                </div>
                <form action="PHP_file/StaffPackageTrasanctionStatus_PHP.php" method="POST" enctype="multipart/form-data">  
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
                       <?php
                       $arrivingmasbate = 'Pending';
                       $deliveryStatus = 'Pending';
                       $package = $detailsRow["id"]; 
                       $sqltransaction = "SELECT * FROM package_transaction WHERE Package_Id = ? AND (Arrive_Masbate=? OR Delivered=?)";

                       // $sqlDetails = "SELECT * FROM client_package WHERE id=? ";
                       $stmt = $conn->prepare($sqltransaction);
                       $stmt->bind_param('iss',$package, $arrivingmasbate, $deliveryStatus);
                       $stmt->execute();
                       $transactionResult = $stmt->get_result();
                       $transactionRow = $transactionResult->fetch_assoc();
                       if ($transactionRow) {
                        if ($transactionRow['Arrive_Masbate'] == 'Pending' && $transactionRow['Delivered'] == 'Pending') {
                       ?>
                    <div class="button1">
                            
                                <input type="hidden" name="idtransaction" value="<?php echo $detailsRow["id"];?>"/>
                                <input type="hidden" name="Arriving_Masbate" value="Confirm">
                                <input type="hidden" name="Delivered" value="Pending">
                                
                            <input type="submit" name="Package_Update2" id="submit" class="submit_btn" value="Confirm Package Arrival" >
                            <input type="submit" name="Delay_package" id="Deley" class="submit_btn" value="Delayed Package Message" >
                    </div>
                        <?php
                                } elseif ($transactionRow['Arrive_Masbate'] == 'Confirm' && $transactionRow['Delivered'] == 'Pending') {
                        ?>
                        <Script>
                               document.querySelector(".button1").style.display = "none";
                        </Script>

                    <div class="button2">
                            
                            <input type="button"  id="submit" class="ImageUpload" value="Confirm Package Received" >
                            <script>
                                        document.querySelector(".ImageUpload").addEventListener("click", function() {
                                        document.querySelector(".Upload_Popup").style.display = "flex";
                                          });

                                    </script>
                        <!-- <input type="submit" name="Package_Update3" id="submit" class="submit_btn" value="Confirm Package Received" > -->
                </div>
                    <?php }
                     elseif ($transactionRow['Delivered'] == 'Confirm') {
                    ?>
                    <span class="Submit_span">The Package Has Been Received</span>

                    <?php  } } ?>
               
                </div>
                </form>
            </div>

<div class="Upload_Popup">
        <div class="Upload_form">
        <form action="PHP_file/StaffPackageTrasanctionStatus_PHP.php" method="POST" enctype="multipart/form-data">  

        <input type="hidden" name="idtransaction" value="<?php echo $detailsRow["id"];?>"/>
                            <input type="hidden" name="Delivered" value="Confirm">
                            <input type="hidden" name="arrive_delivered" value="Delivered">

                            
                <span class="IconclosePayment">
                    <img src="icons/icons8-go-back-50.png" class="iconcloseRefund"alt="">
                </span>

                    <script>
                       document.querySelector(".iconcloseRefund").addEventListener("click", function() {
                                document.querySelector(".Upload_Popup").style.display = "none";
                                });
                    </script>

                <div class="title_Refund">
                 Proof of Delivery
                </div>
               
            <input type="hidden" name="id" value="<?php echo $detailsRow['id'] ?>">


            <div style="text-align: center;">
                <span style="display: block;">Upload Image.</span>
            </div>
            <div class="uploadimage_Proof">
                <div class="imagePreview_Proof">
                    <?php
                    echo "<img class='defaultImage' src='logo/OTS Logo.png' alt='Default Image'>";
                    ?>
                </div>
                <div class="file_btn">
                    <!-- <button id="fileButton" class="filebutton">Upload Image</button> -->
                    <input type="file" name="imageFile" id="imageFile" class="imageFile" accept="image/*" multiple required><br>
                    
                </div>
                <script>
                 document.addEventListener("DOMContentLoaded", function() {
                    const imageFileInput = document.getElementById('imageFile');
                
                    imageFileInput.addEventListener('change', function(event) {
                        const previewDiv = document.querySelector('.imagePreview_Proof');
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
               
                                <!--...your payment form fields...-->
                <div class="button">
                     <input type="submit" name="Package_Update3" id="submit" class="submit_btn" value="Confirm" >
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
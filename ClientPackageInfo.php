<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "transaction");

if (!isset($_SESSION['user_id'])) {
    header('location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$select = mysqli_query($conn, "SELECT * FROM account WHERE id = '$user_id'")
    or die('query Failed');

if(mysqli_num_rows($select) > 0) {
$Profile = mysqli_fetch_assoc($select);
}

$Transaction_ID = $_GET['Transaction_ID'];

if (isset($_GET['success_message'])) {
    $successMessage = htmlspecialchars($_GET['success_message']);
}


?>      

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ClientPackageInfo.css">
    <title>Package Info</title>
</head>
<body>
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


        <div class="container">
        <div class="Back_btn">
                <input type="button" value="Back" onclick="window.location.href='ClientTransactionRecord.php'">
            </div>
        <?php
                $conn = mysqli_connect("localhost", "root", "", "transaction");
                if ($conn->connect_error) {
                    die('Connection failed: ' . $conn->connect_error);
                }
                        $stmt = $conn->prepare("SELECT * FROM client_package WHERE id = ? ");
                        $stmt->bind_param("i", $Transaction_ID); 
                        
                        $stmt->execute();
                        $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) { 
                        $stmt2 = $conn->prepare("SELECT * FROM package_image WHERE Package_ID = ?");
                        $stmt2->bind_param("i", $Transaction_ID);
                        $stmt2->execute();
                        $result3 = $stmt2->get_result();

        ?>
            <div class="title">
                        Transaction Details
                    </div>    
         <div class="package_profile">
            <div class="imagePreview">
                <div class="slides">
                <?php
                while ($imageRow = mysqli_fetch_assoc($result3)) {
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
                    <p><b>Date and Time:</b> <?php echo $row["Date_Time"]; ?></p>
                    <p><b>Package ID:</b>  <?php echo strtoupper($row["PackageNameCode"]); ?></p>
                    <p><b>Package Name:</b>  <?php echo $row["Package_Name"]; ?></p>
                    <p><b>Package Type:</b>  <?php echo $row["Package_Type"]; ?></p>
                    <p><b>Package Weight:</b>  <?php echo $row["Package_Weight"]; ?></p>
                    <p> <b>Package Quantity:</b>  <?php echo $row["Package_Quantity"]; ?></p>
                    
                    </div>

                    <div class="Personal_Info1">
                        <br>
                            <div class="desc2">
                                <h3 >Personal Information</h3>
                            </div>
                        <br>
                            <p><b>Client Name: </b> <?php echo $Profile["Firstname"]; ?> <?php echo $Profile["Lastname"]; ?></p>
                            <p><b>Client Phone Number:</b>  <?php echo $Profile["Phone_Num"]; ?></p>
                            <p><b>Client Email:</b>  <?php echo $Profile["Email"]; ?></p>
                            <br>
                            <p><b>Receiver Name:</b>  <?php echo $row["Receiver_Name"]; ?></p>
                            <p><b>Receiver Phone Number:</b>  <?php echo $row["Receiver_PhoneNum"]; ?></p>
                            <p><b>Receiver Email:</b>  <?php echo $row["Receiver_Email"]; ?></p>
                            <p><b>Receiver Address:</b>  <?php echo $row["Receiver_Address"]; ?></p>
                    </div>

                    <div class="Payment_Transaction">
                        

                            <div class="desc1">
                                    <h3>Payment Transaction</h3>
                            </div>
                            
                            <br>
                    <p><b>Payment Method: </b> <span> <?php echo $row["Payment_Method"]; ?></span></p>
                    <p class="EstimatedPriceid"><b>Estimated Price: </b><span> ₱ <?php echo number_format($row["DeclaredValuePrice"], 2); ?> </span></p>
                    <?php 
                     if ($row['Status_Review'] == 'Pending') {
                    ?>
                    <div class="Update_btn">
                            <input type="button" value="Update Package" class="Edit_Package">

                    </div>
                        <script>
                            document.querySelector(".Edit_Package").addEventListener("click", function() {
                                        document.querySelector(".Show_Popup").style.display = "flex";
                                        });
                        </script>



                        
                    <?php 
                     }
                    ?>

                     <?php 
                        if ($row['Status_Review'] == 'Decline') {
                     ?>
                         <p><b>Reason of Decline:</b><span style="overflow-wrap: break-word; color: red;"><?php echo $row["SR_Desc_Decline"]; ?></span></p>

                    <?php 
                    }?>
                     

                        <?php
                        if ($row['Status_Review'] == 'Accept') {
                            $stmt = $conn->prepare("SELECT * FROM payment_transaction WHERE Package_ID= ?");
                            $stmt->bind_param("s", $Transaction_ID);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $payment = $result->fetch_assoc();
                         
                                
                        ?>
                        <script>
                            window.onload = function() {
                                document.querySelector(".EstimatedPriceid").style.display = "none";
                            };
                        </script>

        <form action="PHP_file/ClientPaymentTransaction.php" method="POST" enctype="multipart/form-data">
            <?php    if ($row['Payment_Method'] == 'Online Payment') {?>
                                
                            <p><b>Total Payment Price: </b> <span>₱ <?php echo number_format($payment["Price_Payment"], 2); ?></span></p>
                            <p id="PaymentDescription"  ><b>Payment Price Reason: </b> <br> <span style="overflow-wrap: break-word; font-size:12px;"> <?php echo $payment["Payment_Desc"]; ?></span></p>
                            <input type="hidden" name="TransactionPaymentID" value="<?php echo $payment["Package_ID"]; ?>">
                            <input type="hidden" name="Admin_PaymentConfirmation" value="Pending">
                            <input type="hidden" name="Client_PaymentConfirmation" value="Confirm">
                    <?php  if ($payment['Client_Confirm'] == 'Pending' || $row['Payment_Confirmation'] == 'Pending'|| $row['Payment_Confirmation'] == 'Decline')  {?>

                        <div class="ClientPayment">
                                <div class="desc1">
                                        <h3>Input Payment Details</h3>
                                </div>
                                
                                <div class="user-details">
                                        <div class="input-box">
                                            <span class="details">Type of Payment :</span>
                                            <select id="Payment_type" name="Payment_type" required>
                                                <option value="">Select payment method</option>
                                                <option value="Gcash">Gcash</option>
                                                <option value="Maya">Maya</option>
                                            </select>
                                                    <Script> 
                                                          document.getElementById("Payment_type").addEventListener("change", function() {
                                                        console.log("Dropdown changed to: " + this.value);
                                                        if (this.value === "Gcash") {
                                                            document.querySelector(".Payment_QR").style.display = "flex";
                                                        } else {
                                                            document.querySelector(".Payment_QR").style.display = "none";
                                                        }
                                                        if (this.value === "Maya") {
                                                            document.querySelector(".Payment_QR2").style.display = "flex";
                                                        } else {
                                                            document.querySelector(".Payment_QR2").style.display = "none";
                                                        }
                                                    });
                                                    document.getElementById("Payment_type").addEventListener("change", function() {
                                                        var selectedValue = this.value;

                                                      
                                                        this.setAttribute("required", selectedValue === "");
                                                    });

                                                    </Script>

                                        </div>
                                  
                                        <div class="input-box">
                                            <span class="details">Screenshot of Reference No. :</span>
                                            <input type="file" name="imageFiles[]" required accept="image/*">
                                        </div>
                                </div>

                                    <div class="terms">
                                        <input type="checkbox" name="agree" id="agree" required>
                                        <a href="#" id="termsLink">Payment Terms & Conditions</a>
                                        <script>
                                            // document.getElementById("agree").addEventListener("change", function() {
                                            //     var termsLink = document.getElementById("termsLink");
                                            //     if (this.checked) {
                                            //         termsLink.target = "_blank";
                                            //     } else {
                                            //         termsLink.target = "_self"; 
                                            //     }
                                            // });
                                
                                            document.querySelector("#termsLink").addEventListener("click", function() {
                                                    document.querySelector(".Show_PopupTerms").style.display = "flex";
                                                    });
                
                                        </script>
                                    </div>
                                    
                                <div class="Onlinebutton">
                                    <input type="hidden" name="TransactionPaymentID" value="<?php echo $payment["Package_ID"]; ?>">
                                    <input type="submit" name="submit_Payment" id="submit" value="Submit Payment">
                                    <input type="submit" name="Cancel_Payment" id="decline" value="Cancel Payment">

                                    <?php  if ($payment['Client_Confirm'] == 'Confirm'){?>
                                        <input type="button"  id="submit" class="showreceipt" value="Payment History">
                                        <script> document.querySelector("#decline").style.display = "none";

                                                 document.querySelector(".showreceipt").addEventListener("click", function() {
                                                    document.querySelector(".Show_PopupImg").style.display = "flex";
                                                    });
                                    </script>
                                    <?php } ?>

                                </div>
                                
                                <script>
                                    document.getElementById("decline").addEventListener("click", function(event) {
                                     
                                        var inputFields = document.querySelectorAll("input, select");
                                        inputFields.forEach(function(inputField) {
                                            inputField.removeAttribute("required");
                                        });

                                      
                                        document.forms[0].submit();
                                    });
                                </script>
                    </div>
                                  
                           
                            
                    <?php }?>
                    
                    <?php 
                     if($row['Payment_Confirmation'] == "Reject"){
                        if ($row['Payment_Method'] == 'Online Payment') {
                    ?>
                         <!-- <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    document.querySelector("#PaymentDescription").style.display = "none";
                                });
                            </script> -->
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
                     }     }
                    ?>




                                
                        <?php 
                                if($row['Payment_Confirmation'] == "Accept"){
                                        $paid = 'Paid';

                        ?>
                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    document.querySelector("#PaymentDescription").style.display = "none";
                                });
                            </script>
                            <p><b>Payment Comfirmation: </b><span><?php echo $paid ?></span></p>
                            <p><b>Reference No. : </b> <span> <?php echo $payment["OR_No"]; ?></span></p>
                            <span style="color: red;" class="clientpackage_online"><b>Payment Accepted!<br>You may now leave the package on site.</b></span>
                        
                            <div class="Refund_btn">
                                    <input type="button" class="RefundBTN" value="Refund Payment">
                                </div>
                                    <script>
                                        document.querySelector(".RefundBTN").addEventListener("click", function() {
                                        document.querySelector(".Refund_Popup").style.display = "flex";
                                          });

                                    </script>
                                    <?php 
                                            if($payment['Refund_Confirmation'] == "Refund"){
                                        ?>
                                        
                                            <div class="Refund_button">
                                                <input type="submit" name="Cancel_Refund_online" id="submit" class="Decline"  value="Cancel Refund">
                                            </div>
                                            <script>
                                                document.addEventListener("DOMContentLoaded", function() {
                                                    document.querySelector(".clientpackage_online").style.display = "none";
                                                    document.querySelector(".online-package").style.display = "none"; 
                                                    document.querySelector(".ReceiverOnsite").style.display = "none";             
                                                });
                                            </script>   
                                             <script>document.querySelector(".Refund_btn").style.display = "none";</script>                       
                                        <?php 
                                            }
                                        ?>

                        <?php if($row['Arriving_Package'] == "On-Site"){?>
                                        <p class="ReceiverOnsite"><b>On-Site Receiver's Name: </b><span><?php echo $row["Onsite_Receiver_Name"]; ?></span></p>
                                        <script>document.querySelector(".clientpackage_online").style.display = "none";</script>
                                        <span style="color: red;" class="online-package"> <b>Your Package is On-Site <br> Please wait for the Transaction Status</b></span>
                             <?php } ?>

                                <?php if($row['Arriving_Package'] == "On-Going" || $row['Arriving_Package'] == "Delivered"){?>
                                    <p><b>On-Site Receiver's Name: </b><span><?php echo $row["Onsite_Receiver_Name"]; ?></span></p>
                                        <script>document.querySelector(".clientpackage_online").style.display = "none";</script>
                                        <script>document.querySelector(".online-package").style.display = "none";</script>
                                        <script>document.querySelector(".Refund_btn").style.display = "none";</script>

                                    <div class="Transaction_btn">
                                        <!-- <span style="color: red; text-align: center;"><b>your package is On-Site!</b></span> -->
                                        <input type="button" value="Go To Transaction" onclick="window.location.href='Clienttransaction.php?Transaction_ID=<?php echo $Transaction_ID; ?>'">
                                        </div>
                        <?php
                                }
                            }
                            if($row['Payment_Confirmation'] == "Refund"){
                                $paid = 'Paid';
                        ?>
                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    document.querySelector("#PaymentDescription").style.display = "none";
                                });
                            </script>
                             <p><b>Payment Comfirmation: </b><span> Refund</span></p>
                             <p><b>Proof of Refunded Payment: </b></p>
                             <div class="ImageTransaction">
                                <?php
                        
                                $stmtImages = $conn->prepare("SELECT Refund_Transaction_SS FROM payment_transaction WHERE Package_ID = ?");
                                $stmtImages->bind_param("s", $row["id"]);
                                $stmtImages->execute();
                                $resultImages = $stmtImages->get_result();

                                $baseImagePath = 'Payment_Screenshots/'; 

                                while ($imageRow = $resultImages->fetch_assoc()) {
                                    $relativePath = trim($imageRow["Refund_Transaction_SS"]);
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
                        <?php 
                                
                                }
                        ?>


        <?php } if ($row['Payment_Method'] == 'On-Site Payment') {?>

                                
            <p><b>Total Payment Price:</b> <span>₱ <?php echo number_format($payment["Price_Payment"], 2); ?> </span></p>
            <p id="PaymentDescription"  style="overflow-wrap: break-word;"><b>Payment Price Reason:</b><span><?php echo $payment["Payment_Desc"]; ?></span></p>

            <?php  if ($payment['Client_Confirm'] == 'Pending') { ?>

                <input type="hidden" name="TransactionPaymentID" value="<?php echo $payment["Package_ID"]; ?>">
                <input type="hidden" name="Admin_PaymentConfirmation" value="Pending">
                <input type="hidden" name="Client_PaymentConfirmation" value="Confirm">

                <div class="terms">
                                    <input type="checkbox" name="agree" id="agree" required>
                                    <a href="#" id="termsLink">Payment Terms & Conditions</a>
                                    <script>
                                        // document.getElementById("agree").addEventListener("change", function() {
                                        //     var termsLink = document.getElementById("termsLink");
                                        //     if (this.checked) {
                                        //         termsLink.target = "_blank";
                                        //     } else {
                                        //         termsLink.target = "_self"; 
                                        //     }
                                        // });
                             
                                        document.querySelector("#termsLink").addEventListener("click", function() {
                                                document.querySelector(".Show_PopupTerms").style.display = "flex";
                                                });
             
                                    </script>
                                </div>
                <div class="Onsite_BTN">
                    <input type="submit" name="Onsite_Accept" id="submit" class="Accept" value="Accept Offer">
                    <input type="submit" name="Onsite_Decline" id="Decline" class="Decline"  value="Decline Offer">
                    <script>
                        document.getElementById("Decline").addEventListener("click", function(event) {
                            
                            var inputFields = document.querySelectorAll("input, select");
                            inputFields.forEach(function(inputField) {
                                inputField.removeAttribute("required");
                            });

                            
                            document.forms[0].submit();
                        });
                    </script>
                </div>

                                    <?php } ?>

                                <?php  if ($payment['Client_Confirm'] == 'Confirm') { ?> 
                                <div class="OnSiteAnounce1">
                                    <br>
                                    <span style="color: red;" class="clientconfirm_on-site"> <b>Please proceed to the Main Office to update the transaction. Thank You!</b></span>
                                </div>

                                <?php }?>

                                <?php   if($row['Payment_Confirmation'] == "Reject"){ ?> 
                                    <input type="hidden" name="TransactionPaymentID" value="<?php echo $payment["Package_ID"]; ?>">
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function() {
                                        
                                            document.querySelector(".OnSiteAnounce1").style.display = "none";
                                        });
                                    </script>

                                <div class="OnSiteAnounce2">
                                    <input type="submit" name="Onsite_ReadyPay" id="submit" class="Accept" value="Ready to Pay">
                                </div>

                                <?php }?>


                                <?php 
                                    if($row['Payment_Confirmation'] == "Accept"){
                                        $paid = 'Paid';
                                ?>
                                     <script>
                                          document.addEventListener("DOMContentLoaded", function() {
                                        
                                            document.querySelector(".OnSiteAnounce1").style.display = "none";
                                        });

                                        document.addEventListener("DOMContentLoaded", function() {
                                            document.querySelector("#PaymentDescription").style.display = "none";
                                        });
                                    </script>
                                    <p><b>Payment Confirmation: </b><span><?php echo $paid ?></span></p>
                                    <p><b>Reference No. : </b> <span> <?php echo $payment["OR_No"]; ?></span></p>

                                    <script>document.querySelector(".clientconfirm_on-site").style.display = "none";</script>
                                    <span style="color: red;" class="ClientPackage_Onsite"><b>Payment Accepted!<br>You may now leave the package On-Site.</b></span>
                                        
                                    <div class="Refund_btn">
                                        <input type="button" class="RefundBTN" value="Refund Payment">
                                    </div>
                                    <script>
                                        document.querySelector(".RefundBTN").addEventListener("click", function() {
                                        document.querySelector(".Refund_Popup").style.display = "flex";
                                        });

                                    </script>
                                        <?php 
                                            if($payment['Refund_Confirmation'] == "Refund"){
                                        ?>
                                             <input type="hidden" name="TransactionPaymentID" value="<?php echo $payment["Package_ID"]; ?>">
                                           <div class="Refund_button">
                                                <input type="submit" name="Cancel_Refund_Onsite" id="submit" class="Decline"  value="Cancel Refund">
                                            </div>
                                            <script>
                                                document.addEventListener("DOMContentLoaded", function() {
                                                    document.querySelector(".ClientPackage_Onsite").style.display = "none";
                                                    document.querySelector(".online-package").style.display = "none"; 
                                                    document.querySelector(".Receiveronsite").style.display = "none"; 
                                                });
                                            </script>
                                             <script>document.querySelector(".Refund_btn").style.display = "none";</script>
                                        <?php 
                                            }
                                        ?>
                                        
                                    <?php if($row['Arriving_Package'] == "On-Site"){?>

                                        <p class="Receiveronsite"><b>On-Site Receiver's Name: </b><span><?php echo $row["Onsite_Receiver_Name"]; ?></span></p>
                                        <script>document.querySelector(".ClientPackage_Onsite").style.display = "none";</script>
                                         <span style="color: red;" class="online-package"> <b>Your Package is On-Site <br> Please wait for the Transaction Status</b></span>
                                    <?php } ?>


                                        <?php if($row['Arriving_Package'] == "On-Going" || $row['Arriving_Package'] == "Delivered"){?>
                                            <p><b>On-Site Receiver's Name: </b><span><?php echo $row["Onsite_Receiver_Name"]; ?></span></p>
                                        <script>document.querySelector(".ClientPackage_Onsite").style.display = "none"; </script>
                                        <script>document.querySelector(".online-package").style.display = "none";</script>
                                        <script>document.querySelector(".Refund_btn").style.display = "none";</script>
                                        
                                        <div class="Transaction_btn">
                                        <!-- <span style="color: red; text-align: center;"><b>your package is On-Site!</b></span> -->
                                        <input type="button" value="Go To Transaction" onclick="window.location.href='Clienttransaction.php?Transaction_ID=<?php echo $Transaction_ID; ?>'">
                                        </div>
                             
                        <?php }
                            } 
                            if($row['Payment_Confirmation'] == "Refund"){
                                $paid = 'Paid';
                        ?>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            document.querySelector(".OnSiteAnounce1").style.display = "none";
                        });
                        
                        document.addEventListener("DOMContentLoaded", function() {
                            document.querySelector("#PaymentDescription").style.display = "none";
                        });
                        document.addEventListener("DOMContentLoaded", function() {
                            document.querySelector(".ClientPackage_Onsite").style.display = "none";
                            document.querySelector(".online-package").style.display = "none"; 

                        });
                             
                    </script>
                     <p><b>Payment Comfirmation: </b><span> Refund</span></p>
                     <span style="color: red;" class="clientconfirm_on-site"> <b>Your refund request has been confirmed. Thank You!</b></span>
                     
                <?php 
                        
                        }
                ?>
                 <?php   }?> 
                        

            <?php } ?>
                            </form>
                    </div>
                </div>
                
                        <div class="Personal_Info2">
                                <br>
                                <div class="desc2">
                                        <h3 >Personal Information</h3>
                                </div>
                                <br>
                                <p><b>Client's Name: </b> <?php echo $Profile["Firstname"]; ?> <?php echo $Profile["Lastname"]; ?></p>
                                <p><b>Client's Phone Number:</b>  <?php echo $Profile["Phone_Num"]; ?></p>
                                <p><b>Client's Email:</b>  <?php echo $Profile["Email"]; ?></p>
                                <br>
                                <p><b>Receiver's Name:</b>  <?php echo $row["Receiver_Name"]; ?></p>
                                <p><b>Receiver's Phone Number:</b>  <?php echo $row["Receiver_PhoneNum"]; ?></p>
                                <p><b>Receiver's Email:</b>  <?php echo $row["Receiver_Email"]; ?></p>
                                <p><b>Receiver's Address:</b>  <?php echo $row["Receiver_Address"]; ?></p>
                        </div>

                        <?php 
                            if (isset($row['Payment_Confirmation']) && $row['Payment_Confirmation'] == 'Decline' || isset($row['Payment_Confirmation']) && $row['Payment_Confirmation'] == 'Reject') {
                                ?>
                                    <div class="paymentdeclineNotif">
                                        <p><b>Masbate Trucking Message:</b> <?php echo $row["Payment_Confirmation_Desc"]; ?></p>
                                    </div>
                                <?php 
                                }
                                ?> 
        </div>
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
                        <div class="title_Update">
                            Update Package Info
                        </div>
                        <form action="PHP_file/ClientFillUpPackage_Update.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="TransactionpackageID" value="<?php echo $Transaction_ID ?>">
                        <!-- Image -->
                        <div class="imagePreview2">
                        <div class="slides">
                            <?php
                            $stmt4 = $conn->prepare("SELECT * FROM package_image WHERE Package_ID = ?");
                            $stmt4->bind_param("i", $Transaction_ID);
                            $stmt4->execute();
                            $updateImage = $stmt4->get_result();

                            while ($imageRow2 = mysqli_fetch_assoc($updateImage)) {
                                $baseImagePath = 'package_Image/';
                                $packageImage2 = $baseImagePath . $imageRow2["Package_Image"];
                                if (file_exists($packageImage2)) {
                                    echo "<div class='slide' style='background-image: url(\"{$packageImage2}\");'></div>";
                                } else {
                                    echo "Image not found: " . $packageImage2;
                                }
                            }
                            ?>
                        </div>
                        <span class="arrow left">◀</span>
                        <span class="arrow right">▶</span>
                    </div>
                    <input type="file" name="imageFiles[]" id="imageFile2" multiple>

                    <script>
                        let slideIndex = 0;
                        const slides = document.querySelector('.imagePreview2 .slides');
                        const leftArrow = document.querySelector('.imagePreview2 .arrow.left');
                        const rightArrow = document.querySelector('.imagePreview2 .arrow.right');

                        leftArrow.addEventListener('click', function () {
                            slides.children[slideIndex].style.display = 'none';
                            slideIndex = (slideIndex - 1 + slides.children.length) % slides.children.length;  
                            slides.children[slideIndex].style.display = 'block';
                        });

                        rightArrow.addEventListener('click', function () {
                            slides.children[slideIndex].style.display = 'none';
                            slideIndex = (slideIndex + 1) % slides.children.length; 
                            slides.children[slideIndex].style.display = 'block';
                        });

                        document.getElementById('imageFile2').addEventListener('change', function (event) {
                            
                            const previewDiv = document.querySelector('.imagePreview2 .slides');
                            previewDiv.innerHTML = '';
                                                
                            
                            for (let i = 0; i < event.target.files.length; i++) {
                                const file = event.target.files[i];
                                const img = document.createElement('img');
                                const slideDiv = document.createElement('div');
                                slideDiv.classList.add('slide'); 
                            
                            
                                img.src = URL.createObjectURL(file);
                                img.onload = function () {
                                    URL.revokeObjectURL(img.src); 
                                }
                            
                           
                                img.style.width = '100%'; 
                                img.style.height = '100%'; 
                            
                             
                                slideDiv.appendChild(img);
                            
                               
                                slides.appendChild(slideDiv);
                            }
                        
                        
                            if (event.target.files.length > 0) {
                               
                                slideIndex = 0;
                                updateSlideshow();
                            }
                        });

                        function updateSlideshow() {
                            const slideElements = document.querySelectorAll('.imagePreview2 .slides .slide');

                            if (slideElements.length > 0) {
                                slideElements[0].style.display = 'block';
                            }

                            if (slideElements.length > 1) {
                                leftArrow.style.display = 'block';
                                rightArrow.style.display = 'block';
                            } else {
                                leftArrow.style.display = 'none';
                                rightArrow.style.display = 'none';
                            }
                        }

                   
                        updateSlideshow();
                    </script>
                    
                    <div class="user-details">
                            <div class="input-box">
                                <span class="details">Package Name</span>
                                <input type="text" name="PackageName" value="<?php echo $row["Package_Name"]; ?>" required oninput="generateQRBasedOnInput();" pattern="[a-zA-Z]*">
                            </div>

                            <input type="hidden" name="qrCodeText" id="hiddenQRText">
                            <input type="hidden" name="Status_Review" value="Pending">

                            <div class="input-box">
                            <span class="details" id="packageTypeLabel">Package Type</span>
                            <select id="PackageTypeDropdown" name="PackageTypeDropdown" onchange="showAdditionalInput(this)">
                                <option value="<?php echo $row["Package_Type"];?>"><?php echo $row["Package_Type"];?></option>
                                <option value="School Supplies">School Supplies</option>
                                <option value="General Merchandise">General Merchandise</option>
                                <option value="Canned Goods">Canned Goods</option>
                                <option value="Perishable Goods">Perishable Goods</option>
                                <option value="Construction Materials">Construction Materials</option>
                                <option value="Medicine">Medicine</option>
                                <option value="Fragile Supplies">Fragile Supplies</option>
                                <option value="Other">Other (Please Specify)</option>
                            </select>

                                <div id="additionalInputContainer" style="display: none;">
                                    <span class="details">Additional Input  <a href="#" id="backLink" onclick="hideAdditionalInput(); return false;" style="color: red; text-decoration:underline;">Back</a></span>
                                    <input type="text" id="AdditionalPackageType" name="AdditionalPackageType" placeholder="Specify Other Package Type">
                                </div>
                            </div>


                            <script>
                                function showAdditionalInput(select) {
                                    var additionalInputContainer = document.getElementById('additionalInputContainer');
                                    var packagetypedesc = document.getElementById('packageTypeLabel');

                                    if (select.value === 'Other') {
                                        select.style.display = 'none';
                                        additionalInputContainer.style.display = 'block';
                                        packagetypedesc.style.display = 'none';
                                    } else {
                                        select.style.display = 'block';
                                        additionalInputContainer.style.display = 'none';
                                        packagetypedesc.style.display = 'block';
                                    }
                                }

                                function hideAdditionalInput() {
                                    var additionalInputContainer = document.getElementById('additionalInputContainer');
                                    var selectDropdown = document.getElementById('PackageTypeDropdown');
                                    var packagetypedesc = document.getElementById('packageTypeLabel');
                                
                                    selectDropdown.style.display = 'block';
                                    selectDropdown.value = ""; 
                                    additionalInputContainer.style.display = 'none';
                                    packagetypedesc.style.display = 'block';
                                }
                                document.getElementById("PackageTypeDropdown").addEventListener("change", function() {
                                    var selectedValue = this.value;
                 
                                    this.setAttribute("required", selectedValue === "");
                                });
                            </script>


                                    <div class="input-box">
                                        <span class="details">Package Weight</span>

                                        <input type="text" id="PackageWeight" name="PackageWeight" placeholder="Kilograms(kg)" value="<?php echo $row["Package_Weight"];?>" required >
                                        <script>
                                            const weightInput = document.getElementById('PackageWeight');
                                                weightInput.addEventListener('input', handleWeightInput);
                                                                
                                                function handleWeightInput() {
                                                    let weightValue = weightInput.value.replace(/\s*kg\s*$/, ''); 
                                                    weightValue = weightValue.replace(/[^0-9.]/g, '');  
                                                    if (weightValue.length > 3) { 
                                                        weightValue = weightValue.substring(0, 3);  
                                                    }
                                                    
                                                    weightInput.value = weightValue + ' kg'; 
                                                    computeTotal(); 
                                                }
                                        </script>
                                    </div>

                            <div class="input-box">
                                <span class="details">Package Quantity</span>
                                <input type="number" id="PackageQuantity" name="PackageQuantity" placeholder="Package Quantity" value="<?php echo $row["Package_Quantity"];?>" required min ="1" max ="1000">
                            </div>
                            <div class="input-box">
                                <span class="details">Estimated Value Price</span>
                                <div class="input-wrapper">
                                    <input type="text" name="DeclaredPrice1" id="PaymentPrice" placeholder="Estimated Value Price" value="₱ <?php echo $row["DeclaredValuePrice"];?>" readonly>
                                    <img src="icons/icons8-help-48.png" alt="" class="helpicon">
                                    <span class="tooltip-text">Please note that this is an estimated price and may not be the final amount payable.</span>
                                </div>

                                <script>
                                    document.getElementById('PackageWeight').addEventListener('input', computeTotal);
                                    document.getElementById('PackageQuantity').addEventListener('input', computeTotal);

                                    function computeTotal() {
                                       
                                        const FIXED_VALUE = 10000;

                                      
                                        const weight = parseFloat(document.getElementById('PackageWeight').value) || 0;
                                        const quantity = parseInt(document.getElementById('PackageQuantity').value) || 0;

                                       
                                        const total = (weight * quantity) + FIXED_VALUE;

                                        
                                      
                               
                                document.getElementById('PaymentPrice').value = `₱ ${total.toFixed(2)}`;

                                        

                                        const totalWithoutPesoSign = total.toFixed(2);

                                     
                                        document.getElementById('PaymentPriceWithoutPesoSign').value = totalWithoutPesoSign;
                                    }
                                </script>
                            </div>

                            <div class="input-box" style="visibility: hidden;">
                                <span class="details">Estimated Value Price</span>
                                    <div class="input-wrapper">
                                        <input type="text" name="DeclaredPrice" id="PaymentPriceWithoutPesoSign"  value="<?php echo $row["DeclaredValuePrice"];?>" readonly>
                                        <img src="icons/icons8-help-48.png" alt="" class="helpicon">
                                        <span class="tooltip-text">Please note that this is an estimated price and may not be the final amount payable.</span>
                                    </div>
                            </div>

                    </div>

                            <div class="title2">
                    <b> Receiver's Information</b>
                    </div>
                    <div class="user-details">
                        <?php 
                              $full_name = $row["Receiver_Name"];
        
                              list($first_name, $last_name) = explode(' ', $full_name, 2);
                        ?>
                        <div class="input-box">
                            <span class="details">Surname</span>
                            <input type="text" id="ReceiverSn" name="ReceiverSn" placeholder="Receiver's Surname"  value="<?php echo $last_name; ?>" pattern="[A-Za-z\s]+" required>
                        </div>

                        <div class="input-box">
                            <span class="details">First Name</span>
                            <input type="text" name="ReceiverFn" placeholder="Receiver's Firstname" value="<?php echo $first_name; ?>" pattern="[A-Za-z\s]+" required>
                        </div>

                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                
                                const letterOnlyInputs = document.querySelectorAll('input[name="ReceiverSn"], input[name="ReceiverFn"]');
                            
                                letterOnlyInputs.forEach(input => {
                                    input.addEventListener('input', (e) => {
                                        
                                        e.target.value = e.target.value.replace(/[^A-Za-z\s]/g, "");
                                    });
                                });
                            });
                        </script>
                                
                            <div class="input-box">
                                <span class="details">Email</span>
                                <input type="text" name="Email" id="email" placeholder="Email" value="<?php echo $row["Receiver_Email"];?>" required>
                                <script>
                                const emailInput = document.getElementById('email');

                                    emailInput.addEventListener('input', function() {
                                    const email = emailInput.value;
                                    if (email.includes('@') && email.includes('.com')) {
                                    emailInput.setCustomValidity('');
                                    } else {
                                    emailInput.setCustomValidity('Please enter a valid email address.');
                                    }
                                });
                                </script>
                            </div>

                            <div class="input-box">
                                <span class="details">Phone Number</span>
                                <input type="text" id="MobileNumber" name="MobileNumber"  value="<?php echo $row["Receiver_PhoneNum"];?>" required pattern="\+63[0-9]{10}">
                                <script>
                                    const mobileInput = document.getElementById('MobileNumber');

                                    mobileInput.addEventListener('input', function() {
                                        let mobileValue = mobileInput.value;

                                        mobileValue = "+63" + mobileValue.replace(/[^0-9]/g, '').substring(2);

                                        if (mobileValue.length > 13) {
                                            mobileValue = mobileValue.substring(0, 13);
                                        }

                                        mobileInput.value = mobileValue;
                                    });

                                    mobileInput.addEventListener('focus', function() {
                                        if (mobileInput.value === "") {
                                            mobileInput.value = "+63";
                                        }
                                    });
                                </script>
                            </div>
                            

                            <div class="input-box">
                            <span class="details" id="Address">Drop-off Address</span>
                            <select id="Masbate Address" name="Address" onchange="showAdditionalInput(this)">
                                <option value="<?php echo $row["Receiver_Address"];?>"><?php echo $row["Receiver_Address"];?></option>
                                <option value="Masbate City.">Masbate City</option>
                                <option value="Milagros Masbate">Milagros Masbate</option>
                            </select>
                    </div>

                    <div class="input-box" style="visibility: hidden;"></div>

                    </div>
                    <br>
                    <div class="payment-details">
                                    <?php 
                                        $Payment_Method = $row['Payment_Method'];
                                        ?>
                            <input type="radio" name="payment" id="dot-1" value="On-Site Payment" <?php echo ($Payment_Method === 'On-Site Payment') ? 'checked' : ''; ?>>
                            <input type="radio" name="payment" id="dot-2" value="Online Payment"  <?php echo ($Payment_Method === 'Online Payment') ? 'checked' : ''; ?>>
                            
                            <span class="payment-title"><b>Select Payment Method</b></span>

                            <div class="category">
                                <label for="dot-1">
                                    <span class="dot one"></span>
                                    <span class="payment"><b>On-Site Payment</b></span>
                                </label>

                                <label for="dot-2">
                                    <span class="dot two"></span>
                                    <span class="payment"><b>Online Payment</b></span>
                                </label>
                            </div>
                        </div>
                    <!-- <h4 class="Alert">Your package has been sumbitted</h4>  -->
                            <div class="button">
                            <input type="submit" name="submit" id="submit" value="Submit" onclick="generateQR()">
                            </div>
                    </form>
                    <script>
                function generateQR() {
                
                        var qrText = Math.random().toString(36).substring(2, 10);
                        document.getElementById("hiddenQRText").value = qrText; 
                }
                    </script>
            </div>
         </div>
         
    <div class="Refund_Popup">
        <div class="Refund_form">
            <form action="PHP_file/ClientRefundPayment.php" method="POST" enctype="multipart/form-data">
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
                       if (isset($row['Payment_Method']) && $row['Payment_Method']== 'Online Payment') {
                ?>

           
            <div class="uploadimage_Refund">
                <div class="imagePreview_Refund">
                    <?php
                    echo "<img class='defaultImage' src='logo/OTS Logo.png' alt='Default Image'>";
                    ?>
                </div>
                <div class="file_btn">
                  
                    <input type="file" name="imageFile" id="imageFile" class="imageFile" accept="image/*" multiple required><br>
                    <span style="margin-left:20%;">Upload your QR Scan</span>
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
               
                                    
                <div class="input-box">
                    <input type="hidden" name="id" value="<?php echo $row['id'] ?>">

                    <span class="details">Type of Refund</span>
                                            <select id="Payment_type" name="Payment_type" required>
                                                <option value="">Select payment method</option>
                                                <option value="Gcash">Gcash</option>
                                                <option value="Maya">Maya</option>
                                                <script>
                                                      document.getElementById("Payment_type").addEventListener("change", function() {
                                                        var selectedValue = this.value;

                                                        this.setAttribute("required", selectedValue === "");
                                                    });
                                                </script>
                                            </select>
                </div> 
            
                <div class="input-box">
                        <span class="details">Provide the reason for the Refund</span>
                        <input type="text" name="RefundPaymentDesc" id="PaymentPriceDesc" placeholder="Enter reason for the Refund" >
                           
                </div> 
            <?php 
            }   if ($row['Payment_Method'] == 'On-Site Payment') {
            ?>
                <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                <div class="input-box">
                        <span class="details">Provide the reason for the Refund</span>
                        <input type="text" name="RefundPaymentDesc" id="PaymentPriceDesc" placeholder="Enter reason for the Refund" required>
                           
                </div> 

            <?php 
            }
            ?>
                            
                <div class="button">
                    <input type="submit" name="submit_Refund" id="submit" value="Submit">
                </div>
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
                    <!-- <th></th>
                    <th></th> -->
                </tr>
            <?php
            // Database connection
            $packageid = $row['id'];
            $sql2 = "SELECT * FROM payment_screenshots WHERE Package_id  = $packageid";       
            $result2 = $conn->query($sql2);
            
            if ($result2->num_rows > 0) {
                $counter=1;
                while ($row1 = $result2->fetch_assoc())  {
                    

                
            ?>
                <!-- <form action="PHP_file/AdminPaymentTransaction_Decline.php" method="POST"> -->
        <!-- <div id="onlinePayment" style="display: none;">
                    <h3>Online Payment</h3> -->
                    <input type="hidden" name="Image_id" value="<?php echo $row1["id"];?>"/>
                <tr >
                    <td><?php echo $counter++; ?></td>
                    <td><img src="Payment_Screenshots/<?php echo $row1["Screenshot_Payment"]; ?>" alt="Payment Screenshot"></td>
                    <td><?php echo $row1["Status_image"]; ?></td>
                    

                </tr>
                <!-- </form> -->
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
    <?php   
        }
                }
        ?>
        
        <div class="Payment_QR">
            <div class="Payment_case"> 
            
            <div class="title_Delete">
                Payment QR Code
            </div>

            <div class="QRCode_SS">
                <img src="Payment_QR/Sample Gcash.jpg" alt="">
            </div>

            <div class="Done_btn">
                <button type="button" class="close">Close</button>
            </div>
            <script>

                document.querySelector(".close").addEventListener("click", function() {
                console.log("Close clicked");
                document.querySelector(".Payment_QR").style.display = "none";
            });
            </script>
        
            </div>
        </div>

        <div class="Payment_QR2">
            <div class="Payment_case"> 
            
            <div class="title_Delete">
                Payment QR Code
            </div>

            <div class="QRCode_SS">
                <img src="Payment_QR/SampleMaya.jpg" alt="">
            </div>

            <div class="Done_btn">
                <button type="button" class="close2">Close</button>
            </div>
            <script>

                document.querySelector(".close2").addEventListener("click", function() {
                console.log("Close clicked");
                document.querySelector(".Payment_QR2").style.display = "none";
            });
            </script>
        
            </div>
        </div>



  



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

<div class="Show_PopupTerms" >
        <div class="add_form">
            <span class="Icon_closeterms" id="icon_Close">
                    <img src="icons/icons8-go-back-50.png" class="Icon-close"alt="">
                </span>
                <script>
                    document.querySelector(".Icon_closeterms").addEventListener("click", function() {
                            document.querySelector(".Show_PopupTerms").style.display = "none";
                            });
                </script>
        
                <div class="title_Review">
                   Payment Terms & Conditions
                </div>
              
                <div class="description">
            <div class="titledescription">
                <p>Eligibility for Refund</p>
            </div>
            <div class="Infodescription">
                <p>Refunds are applicable only for products or
                    services that are deemed defective, damaged,
                    or do not meet the specifications as described on our platform.</p>
            </div>

            <div class="titledescription">
                <p>Refund Period</p>
            </div>
            <div class="Infodescription">
                <p> The refund period is 5 days from the date of purchase unless
                    otherwise specified for a particular product or service.</p>
            </div>

            <div class="titledescription">
                <p>Requesting a Refund</p>
            </div>
            <div class="Infodescription">
                <p> Customers may be required to provide proof of purchase and details 
                about the reason for the refund request, and proof for the payment like the receipt.</p>
            </div>

            <div class="titledescription">
                <p>Refund Process</p>
            </div>
            <div class="Infodescription">
                <p> Once a refund request is received and approved, 
                    the refund will be processed within 3 days. 
                    Refunds will be issued using the same method 
                    of payment used for the original purchase.</p>
            </div>

            <div class="titledescription">
                <p>Cancellation Policy</p>
            </div>
            <div class="Infodescription">
                <p> For services with recurring billing,
                    customers can cancel their subscription at any time,
                    but refunds for the current billing period may not be provided.
                    Cancellations for physical product orders must be made before 
                    the product is shipped to be eligible for a refund.</p>
            </div>

            <div class="titledescription">
                <p>Changes to Refund Policy</p>
            </div>
            <div class="Infodescription">
                <p> Masbate Trucking reserves the right to modify this refund policy
                    at any time without prior notice. Any changes will be effective immediately
                    upon posting the updated policy on our website.</p>
            </div>

            <div class="titledescription">
                <p>Contact Information</p>
            </div>
            <div class="Infodescription">
                <p>  For any questions or concerns regarding our refund policy,
                    please contact our customer support team at +639219775000.</p>
            </div>
        </div>
            
        </div>
    </div>



</body>
</html>
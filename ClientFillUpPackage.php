<?php
    if (isset($_GET['success_message'])) {
        $successMessage = htmlspecialchars($_GET['success_message']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ClientFillUpPackage.css">
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <title>Fill-Up Package</title>
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
    <div class="backbtn">
            <input type="button" value="Back" onClick="location.href='ClientDashboard.php'">
        </div>
        <div class="title">
            Fill Up Package
        </div>
    <div class="title2">
         <b> Package Information </b>
    </div>
        <form action="PHP_file/ClientFillUpPackage_PHP.php" method="post" autocomplete="off" enctype="multipart/form-data">

        <div class="uploadimage">
            <div id="imagePreview" class="imagePreview">
                <div class="slides"></div>
                <div class="arrow left">◀</div>
                <div class="arrow right">▶</div>
            </div>

    <div class="file_btn">
        <button id="fileButton" class="filebutton">Upload Image(s)</button>
        <input type="file" name="imageFiles[]" id="imageFile" class="imageFile" accept="image/*" multiple style="display: none;">
    </div>

    <script>
        const imageFileInput = document.getElementById('imageFile');
        const slidesContainer = document.querySelector('.imagePreview .slides');
        let slideIndex = 0;

        const fileButton = document.getElementById('fileButton');

        fileButton.addEventListener('click', function() {
            imageFileInput.click();
        });

        imageFileInput.addEventListener('change', function() {
            slidesContainer.innerHTML = '';

            for (let i = 0; i < this.files.length; i++) {
                const file = this.files[i];

                if (file) {
                    const reader = new FileReader();
                    const slideDiv = document.createElement('div');
                    slideDiv.className = 'slide';

                    reader.onload = function(e) {
                        slideDiv.style.backgroundImage = `url(${e.target.result})`;
                        slidesContainer.appendChild(slideDiv); 
                    }
                    reader.readAsDataURL(file);
                }
            }

            if (this.files.length > 1) {
                document.querySelector('.arrow.left').style.display = 'block';
                document.querySelector('.arrow.right').style.display = 'block';
            } else {
                document.querySelector('.arrow.left').style.display = 'none';
                document.querySelector('.arrow.right').style.display = 'none';
            }
        });

        document.querySelector('.arrow.left').addEventListener('click', function() {
            slideIndex = Math.max(slideIndex - 1, 0);
            updateSlider();
        });

        document.querySelector('.arrow.right').addEventListener('click', function() {
            slideIndex = Math.min(slideIndex + 1, slidesContainer.children.length - 1);
            updateSlider();
        });

        function updateSlider() {
            const offset = -slideIndex * 150; // 200 is the width of each slide
            slidesContainer.style.transform = `translateX(${offset}px)`;
        }
    </script>
</div>


        <div class="user-details">
                <div class="input-box">
                    <span class="details">Package Name</span>
                    <input type="text" name="PackageName" placeholder="Package Name" required>
                </div>

                <input type="hidden" name="qrCodeText" id="hiddenQRText">
                <input type="hidden" name="Status_Review" value="Pending">

                <!-- <div class="input-box">
                    <span class="details">Package Type</span>
                    <input type="text" name="PackageType" placeholder="Enter Package Type" required>
                </div> -->

                <div class="input-box">
                  <span class="details" id="packageTypeLabel">Package Type</span>
                  <select id="PackageTypeDropdown" name="PackageTypeDropdown" onchange="showAdditionalInput(this)">
                    <option value="">Select Package Type</option>
                    <option value="School Supplies">School Supplies</option>
                    <option value="General Merchandise">General Merchandise</option>
                    <option value="Canned Goods">Canned Goods</option>
                    <option value="Perishable Goods">Perishable Goods</option>
                    <option value="Construction Materials">Construction Materials</option>
                    <option value="Medicine">Medicine</option>
                    <option value="Fragile Supplies">Fragile Supplies</option>
                    <option value="Other">Others (Please Specify)</option>
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
                        selectDropdown.value = ""; // Reset the dropdown value
                        additionalInputContainer.style.display = 'none';
                        packagetypedesc.style.display = 'block';
                    }
                    document.getElementById("PackageTypeDropdown").addEventListener("change", function() {
                        var selectedValue = this.value;
                     
                        this.setAttribute("required", selectedValue === "");
                    });
                </script>


                        <div class="input-box">
                            <span class="details">Package Weight(per unit)</span>

                            <input type="text" id="PackageWeight" name="PackageWeight" placeholder="Kilograms(kg)" required >
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
                    <input type="number" id="PackageQuantity" name="PackageQuantity" placeholder="Package Quantity" required min ="1" max ="10000">
                </div>
                <div class="input-box">
                        <span class="details">Estimated Price</span>
                        <div class="input-wrapper">
                            <input type="text" name="DeclaredPrice1" id="PaymentPrice" placeholder="₱ Estimated Price" readonly>
                            <img src="icons/icons8-help-48.png" alt="" class="helpicon">
                            <span class="tooltip-text">Please note that this is an estimated price and may not be the final amount payable.</span>
                        </div>

                        <script>
                            document.getElementById('PackageQuantity').addEventListener('input', computeTotal);

                            function computeTotal() {
                          
                                const FIXED_VALUE = 10000;

                                const weight = parseFloat(document.getElementById('PackageWeight').value) || 0;
                                const quantity = parseInt(document.getElementById('PackageQuantity').value) || 0;

                            
                                console.log("Weight:", weight);
                                console.log("Quantity:", quantity);

                           
                                const total = (weight * quantity) + FIXED_VALUE;

                              
                                console.log("Total:", total);

                                
                                document.getElementById('PaymentPrice').value = `₱ ${total.toFixed(2)}`;

                                const totalWithoutPesoSign = total.toFixed(2);

                                
                                document.getElementById('PaymentPriceWithoutPesoSign').value = totalWithoutPesoSign;
                            }
                        </script>
                    </div>

                <div class="input-box" style="visibility: hidden;">
                <span class="details">Estimated Value Price</span>
                        <div class="input-wrapper">
                            <input type="text" name="DeclaredPrice" id="PaymentPriceWithoutPesoSign" placeholder="₱ Estimated Value Price" readonly>
                            <img src="icons/icons8-help-48.png" alt="" class="helpicon">
                            <span class="tooltip-text">Please note that this is an estimated price and may not be the final amount payable.</span>
                        </div>
            </div>

        </div>

                <div class="title2">
          <b> Receiver's Information</b>
        </div>
        <div class="user-details">
            <div class="input-box">
                <span class="details">Last Name</span>
                <input type="text" id="ReceiverSn" name="ReceiverSn" placeholder="Receiver's Last Name" pattern="[A-Za-z\s ]+" required>
            </div>

            <div class="input-box">
                <span class="details">First Name</span>
                <input type="text" name="ReceiverFn" id="ReceiverFn" placeholder="Receiver's First Name" pattern="[A-Za-z\s ]+" required>
            </div>

            <script>
                function capitalizeFirstLetter(inputId) {
                    var inputElement = document.getElementById(inputId);
                    var inputValue = inputElement.value;
                    inputElement.value = inputValue.charAt(0).toUpperCase() + inputValue.slice(1);
                }

                document.getElementById('ReceiverSn').addEventListener('blur', function () {
                    capitalizeFirstLetter('ReceiverSn');
                });

                document.getElementById('ReceiverFn').addEventListener('blur', function () {
                    capitalizeFirstLetter('ReceiverFn');
                });
        </script>

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
                    <span class="details">Email Address</span>
                    <input type="text" name="Email" id="email" placeholder="Email Address" required>
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
                    <input type="text" id="MobileNumber" name="MobileNumber" value="+63" required pattern="\+63[0-9]{10}">
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
                
                <!-- <div class="input-box">
                    <span class="details">Address</span>
                    <input type="text" name="Address" placeholder="Address" required>
                </div> -->
                <div class="input-box">
                  <span class="details" id="Address">Drop-off Address</span>
                  <select id="Masbate Address" name="Address" >
                    <option value="Masbate City.">Masbate City</option>
                    <option value="Milagros Masbate">Milagros Masbate</option>
                    <!-- <option value="Aroroy Masbate">Aroroy Masbate</option>
                    <option value="Baleno Masbate">Baleno Masbate</option>
                    <option value="Balud Masbate">Balud Masbate</option>
                    <option value="Batuan Masbate">Batuan Masbate</option>
                    <option value="Cataingan Masbate">Cataingan Masbate</option>
                    <option value="Cawayan Masbate">Cawayan Masbate</option>
                    <option value="Claveria Masbate">Claveria Masbate</option>
                    <option value="Dimasalang Masbate">Dimasalang Masbate</option>
                    <option value="Esperanza Masbate">Esperanza Masbate</option>
                    <option value="Mandaon Masbate">Mandaon Masbate</option> -->
                    <!-- <option value="Mobo Masbate">Mobo Masbate</option>
                    <option value="Monreal Masbate">Monreal Masbate</option>
                    <option value="Palanas Masbate">Palanas Masbate</option>
                    <option value="Pio V. Corpus Masbate">Pio V. Corpus Masbate</option>
                    <option value="Placer Masbate">Placer Masbate</option>
                    <option value="San Fernando Masbate">San Fernando Masbate</option>
                    <option value="San Jacinto Masbate">San Jacinto Masbate</option>
                    <option value="San Pascual Masbate">San Pascual Masbate</option>
                    <option value="Uson Masbate">Uson Masbate</option> -->
                  </select>
        </div>

        <div class="input-box" style="visibility: hidden;"></div>

        </div>
        <br>
        <div class="payment-details">
                <input type="radio" name="payment" id="dot-1" value="On-Site Payment">
                <input type="radio" name="payment" id="dot-2" value="Online Payment">
                 
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
        <h4 class="Alert">Your package has been sumbitted</h4> 
                <div class="button">
                <input type="submit" name="submit" id="submit" value="Submit" onclick="generateQR()">
                </div>
        </form>
        <script>
    function generateQR() {
        // var inputValue = document.getElementsByName("PackageName")[0].value;
        
            var qrText = Math.random().toString(36).substring(2, 10); 
            document.getElementById("hiddenQRText").value = qrText; 
    }
</script>
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

</body>
</html>
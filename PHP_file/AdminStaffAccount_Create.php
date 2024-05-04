<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "transaction");
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location: index.php');
    exit; 
}

if(isset($_POST["submit"])){
    $Firstname = $_POST["Firstname"];
    $Lastname = $_POST["Lastname"];
    $Username = $_POST["Username"];
    $Email = $_POST["Email"];
    $PhoneNumber =$_POST["PhoneNum"];
    $Password = $_POST["Password"];
    $confirmpassword = $_POST["ConfirmPassword"];
    $gender= $_POST["gender"];
    $usertype= $_POST["Usertype"];
    $ActiveStatus =$_POST["ActiveStatus"];
    $Verified = 'Confirm';

    $duplicate = mysqli_query($conn, "SELECT * FROM account WHERE username = '$Username' OR email = 'email'");
    if(mysqli_num_rows($duplicate)> 0 ){
        // echo
        // "<script> window.location.href = '../AdminStaffAccount.php?addMsg=' + encodeURIComponent('Username or Email Has Already Taken'); </script>";
        echo "<script> alert('Username or Email Has Already Taken');
        window.location = '../AdminStaffAccount.php'</script>";
    }
    else{

        if($Password == $confirmpassword){
            $query = "INSERT INTO account(Firstname, Lastname, Username, Email, Phone_Num, Gender, Password, User_type, Active_Status, Verified) VALUES('$Firstname','$Lastname','$Username','$Email','$PhoneNumber','$gender','$Password', '$usertype','$ActiveStatus', '$Verified')";
            mysqli_query($conn, $query);
            // echo
            // "<script> window.location.href = '../AdminStaffAccount.php?addMsg=' + encodeURIComponent('Registered Successfully');</script>";
            // echo "<script> alert('Registered Successfully');
            // window.location = '../AdminStaffAccount.php'</script>";
            $successMessage = "Registered Successfully.";
                header("Location: ../AdminStaffAccount.php?success_message=" . urlencode($successMessage));
                exit;

        }

        else{
            // echo "<script>
            // document.getElementById('confirmpassword').setCustomValidity('Passwords do not match.');
            // </script>";
            echo "<script> alert('Passwords do not match');
            window.location = '../AdminStaffAccount.php'</script>";
        }
    }
}

    ?>
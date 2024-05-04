<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "transaction");


if(isset($_POST["Update"])){
    $id = $_POST['id'];  
    $Firstname = $_POST["Firstname"];
    $Lastname = $_POST["Lastname"];
    $Username = $_POST["Username"];
    $Email = $_POST["Email"];
    $PhoneNumber = $_POST["PhoneNum"];

    $duplicate = mysqli_query($conn, "SELECT * FROM account WHERE (username = '$Username' OR email = '$Email') AND id != '$id'");
    if(mysqli_num_rows($duplicate) > 0 ){
        // $_SESSION['message'] = "Username or Email already exists";
        // $_SESSION['msg_type'] = "danger";
        // header("Location: ../AdminStaffAccount.php?id=$id");
        echo "<script> alert('Username or Email Has Already Taken');
        window.location = '../AdminStaffAccount.php'</script>";
    }
    else{
        $query = "UPDATE account SET Firstname = '$Firstname', Lastname = '$Lastname', Username = '$Username', Email = '$Email', Phone_Num = '$PhoneNumber' WHERE id = '$id'";
        mysqli_query($conn, $query);
            // $_SESSION['message'] = "Account successfully updated";
            // $_SESSION['msg_type'] = "success";
            // header("Location: ../AdminStaffAccount.php?id=$id");
            // echo "<script> alert('Account successfully updated');
            // window.location = '../AdminStaffAccount.php'</script>";

            $successMessage = "Updated Successfully.";
            header("Location: ../AdminStaffAccount.php?success_message=" . urlencode($successMessage));
            exit;
    }
}
?>
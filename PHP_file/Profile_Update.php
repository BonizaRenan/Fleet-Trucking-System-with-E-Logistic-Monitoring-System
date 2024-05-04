<?php
session_start();

// Error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// DB connection
$conn = mysqli_connect("localhost", "root", "", "transaction");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST["submit_change"])) {
    $id = $_POST['userid'];

    $Firstname = $_POST["firstname"];
    $Lastname = $_POST["lastname"];
    $Username = $_POST["Username"];
    $Email = $_POST["email"];
    $PhoneNumber = $_POST["PhoneNum"];
    $Password = $_POST["Passwordtype"];
    $ProfileImage = null;

    $stmt = $conn->prepare("SELECT * FROM account WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

        if ($Password == $row["Password"]) {

                    if(isset($_FILES["imageFiles"]["name"]) && $_FILES["imageFiles"]["error"] == 0) {
                        $target_dir = "../Profile_Image/";
                        $ProfileImage = $target_dir . basename($_FILES["imageFiles"]["name"]);
                        
                        if(!move_uploaded_file($_FILES["imageFiles"]["tmp_name"], $ProfileImage)) {
                            echo "Failed to upload image.";
                            exit;
                        }
                    }
                
                    $stmt2 = $conn->prepare("SELECT * FROM account WHERE (username = ? OR email = ?) AND id != ?");
                    $stmt2->bind_param("ssi", $Username, $Email, $id);
                    $stmt2->execute();
                    $result2 = $stmt2->get_result();

                    if($result2->num_rows > 0) {
                        $_SESSION['message'] = "Username or Email already exists";
                        $_SESSION['msg_type'] = "danger";
                        header("Location: ../Profile.php");
                        exit;
                    
                    } 

                    $stmt3 = $conn->prepare("UPDATE account SET Firstname = ?, Lastname = ?, Username = ?, Email = ?, Phone_Num = ?, Profile_image = ? WHERE id = ?");
                    $stmt3->bind_param("ssssssi", $Firstname, $Lastname, $Username, $Email, $PhoneNumber, $ProfileImage, $id);
                    if ($stmt3->execute()) {
                        $successMessage = "Updated Successfully.";
                        header("Location: ../Profile.php?success_message=" . urlencode($successMessage));
                        exit;
                    } else {
                        echo "Error: " . $stmt3->error;
                    }
        }

        else {
            echo "<script> alert('Current Password not Match!');
                            window.location = '../Profile.php'</script>";
            exit;
        }
}

if(isset($_POST["change_pass"])) {

    $id = $_POST['userid'];  
    $currentpassword = $_POST["CurrentPassword"];
    $Password = $_POST["Password"];
    $confirmpassword = $_POST["confirmpassword"];

    $stmt = $conn->prepare("SELECT * FROM account WHERE id =?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($Password !== $confirmpassword) {
        echo "<script> alert('Password not Match!');
                        window.location = '../Profile.php'</script>";
        exit;   
    }

    if ($currentpassword == $row["Password"]) {
        $stmt = $conn->prepare("UPDATE account SET Password = ? WHERE id = ?");
        $stmt->bind_param("si", $Password, $id);
        if ($stmt->execute()) {
            $successMessage = "Changed Password Successfully.";
            header("Location: ../Profile.php?success_message=" . urlencode($successMessage));
            exit;
        } 
    }
    else {
        echo "<script> alert('Current Password not Match!');
                        window.location = '../Profile.php'</script>";
        exit;
    }
}
?>

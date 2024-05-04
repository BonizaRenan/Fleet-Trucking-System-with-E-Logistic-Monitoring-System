<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "transaction");

if(isset($_POST["id"])){
    $id = $_POST['id'];  

    // Delete query
    $query = "DELETE FROM account WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        // $_SESSION['message'] = 'Successfully deleted the account.';
        // $_SESSION['msg_type'] = 'success';
        // $_SESSION['deleted'] = true;  // Set the deleted status as true
        // echo "<script> alert('Successfully deleted the account');
        // window.location = '../AdminStaffAccount.php'</script>";

        $successMessage = "Deleted Successfully.";
        header("Location: ../AdminStaffAccount.php?success_message=" . urlencode($successMessage));
        exit;
    } else {
        // $_SESSION['message'] = 'An error occurred while deleting the account.';
        // $_SESSION['msg_type'] = 'danger';
        echo "<script> alert('An error occurred while deleting the account.');
        window.location = '../AdminStaffAccount.php'</script>";
    }
    
    header("location: ../AdminStaffAccount.php");
    exit;
}
?>
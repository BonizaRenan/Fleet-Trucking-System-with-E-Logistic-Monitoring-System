<?php
session_start();
$user_id = $_SESSION['user_id'];
$conn = mysqli_connect("localhost", "root", "", "transaction");

mysqli_query($conn, "UPDATE account SET Active_Status = 'Offline' WHERE id =".$user_id);
// Destroy the session to log out the user
session_destroy();

// Redirect the user to the login page or any other page as needed
header('Location: index.php');
exit();
?>
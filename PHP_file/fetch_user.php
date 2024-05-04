<?php
header('Content-Type: application/json');

if(isset($_GET["id"])) {
    $stmt = $conn->prepare("SELECT * FROM account WHERE id= ?");
    $stmt->bind_param("s", $_GET["id"]);
    $stmt->execute();
    
    $result = $stmt->get_result();
    if($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}
?>
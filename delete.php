<?php
include 'dbconn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // 1. I-delete ang napiling user
    $sql = "DELETE FROM users WHERE id = $id";
    $conn->query($sql);
    
    // 2. Ayusin ang mga ID para sunod-sunod ulit
    $conn->query("SET @count = 0");
    $conn->query("UPDATE users SET id = @count:= @count + 1");
    
    // 3. I-reset ang auto-increment para sumunod sa susunod na numero
    $result = $conn->query("SELECT MAX(id) as max_id FROM users");
    $row = $result->fetch_assoc();
    $next_id = $row['max_id'] ? $row['max_id'] + 1 : 1;
    $conn->query("ALTER TABLE users AUTO_INCREMENT = $next_id");
    
    header("Location: index.php?msg=deleted");
    exit;
}
?>
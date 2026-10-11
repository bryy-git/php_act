
<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "php_db";

// Create connection
$conn = new mysqli($host, $user, $pass, $db, 3306);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "";
?>


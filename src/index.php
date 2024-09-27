<?php
$servername = "mysql-db";
$username = "user";
$password = "user_password";
$dbname = "sampledb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully to MySQL database! ";
echo "Line 1" . PHP_EOL . "Line 2";

echo "update";
echo "new!!";
echo "Hello, World!<br>";

?>




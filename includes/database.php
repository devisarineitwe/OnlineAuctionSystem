<?php
// Define your database parameters
$host = "localhost";
$dbname = "online_auction_kab";
$username = "root";
$password = "";

// Create a PDO object and connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set the character set to UTF-8
    $pdo->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
    // Handle connection errors
    die("Connection failed: " . $e->getMessage());
}
?>

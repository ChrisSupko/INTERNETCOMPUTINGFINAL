<?php
$host = 'localhost';
$dbname = 'users';
$username = 'root';            // Default username for XAMPP
$password = '';                // Default password for XAMPP MySQL (empty by default)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

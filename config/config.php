<?php
$host = 'localhost';
$dbname = 'primeraliving'; // database name
$user = 'root'; // default XAMPP
$pass = '';     // default XAMPP

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}
?>

   
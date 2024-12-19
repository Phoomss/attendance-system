<?php
$host = "localhost";
$db = "access_system";
$user = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host$host;dbname=$db;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

<?php
$host = "localhost";
$db   = "demo_db";
$user = "root";
$pass = "";
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8";

try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    die("Lỗi kết nối DB: " . $e->getMessage());
}
?>

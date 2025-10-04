<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=mevlanablog;charset=utf8mb4", "mevlana", "ApEBFdt/2/CZ*XAa"); // kullanıcı adı ve şifreyi senin XAMPP ayarına göre güncelle
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>

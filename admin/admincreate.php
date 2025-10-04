<?php
$pdo = new PDO("mysql:host=localhost;dbname=mevlanablog;charset=utf8mb4", "mevlana", "ApEBFdt/2/CZ*XAa");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$username = "mevlanabazaar";
$password = password_hash("Mevlana1453", PASSWORD_BCRYPT);

$stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
$stmt->execute([$username, $password]);

echo "Admin kullanıcı eklendi.";

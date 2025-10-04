<?php
require_once 'config.php';
require_once 'auth.php';

$id = $_GET['id'] ?? null;

if ($id) {
    // Önce çevirileri sil
    $pdo->prepare("DELETE FROM post_translations WHERE post_id = ?")->execute([$id]);
    // Sonra postu sil
    $pdo->prepare("DELETE FROM posts WHERE id = ?")->execute([$id]);
}

header("Location: blog_list.php");
exit;

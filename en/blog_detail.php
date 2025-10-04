<?php
require_once '../admin/config.php';

$slug = $_GET['slug'] ?? '';
$lang = $_GET['lang'] ?? 'tr';
$lang = in_array($lang, ['tr', 'en', 'ar']) ? $lang : 'tr';

if (!$slug) {
    die("Slug eksik.");
}

$stmt = $pdo->prepare("
    SELECT p.*, pt.title, pt.content, pt.meta_title, pt.meta_description, pt.keywords
    FROM posts p
    JOIN post_translations pt ON p.id = pt.post_id
    WHERE p.slug = ? AND pt.lang_code = ? AND p.is_published = 1
    LIMIT 1
");
$stmt->execute([$slug, $lang]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Blog bulunamadı.");
}

?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['meta_title'] ?: $post['title']) ?></title>
    <meta name="description" content="<?= htmlspecialchars($post['meta_description']) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($post['keywords']) ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container my-5">
    <div class="card shadow">
        <?php if (!empty($post['image_path'])): ?>
            <img src="../upload/<?= htmlspecialchars($post['image_path']) ?>" class="card-img-top" alt="<?= htmlspecialchars($post['title']) ?>" style="max-height: 600px; object-fit: cover;">
        <?php endif; ?>

        <div class="card-body">
            <h1 class="card-title"><?= htmlspecialchars($post['title']) ?></h1>
            <p class="text-muted"><?= date('d M Y', strtotime($post['created_at'])) ?></p>
            <div class="card-text">
                <?= ($post['content']) ?>
            </div>

            <a href="blog_module.php?lang=<?= $lang ?>" class="btn btn-secondary mt-4">
                <?= $lang === 'tr' ? '← Bloglara Dön' : ($lang === 'en' ? '← Back to Blogs' : '← العودة إلى المدونات') ?>
            </a>
        </div>
    </div>
</div>

</body>
</html>

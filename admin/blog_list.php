<?php
require_once 'config.php'; 
require_once 'auth.php';

$lang = $_GET['lang'] ?? 'tr';

$query = $pdo->prepare("
    SELECT p.id, pt.title, p.slug, p.image_path, p.created_at
    FROM posts p
    JOIN post_translations pt ON p.id = pt.post_id
    WHERE pt.lang_code = :lang
    ORDER BY p.created_at DESC
    
");
$query->execute(['lang' => $lang]);
$posts = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Blog Listesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📚 Blog Listesi <span class="badge bg-secondary"><?= strtoupper($lang) ?></span></h2>
        <div class="d-flex justify-content-end">
        <a href="blog_add.php" class="btn btn-success mx-2">➕ Yeni Blog Ekle</a>
        <a href="logout.php" class="btn btn-danger">Çıkış Yap</a>
        </div>
    </div>

    <?php if (empty($posts)): ?>
        <div class="alert alert-info">Bu dilde henüz blog eklenmemiş.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Başlık</th>
                        <th scope="col">Slug</th>
                        <th scope="col">Görsel</th>
                        <th scope="col">Tarih</th>
                        <th scope="col" style="width: 150px;">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><?= $post['id'] ?></td>
                        <td><?= htmlspecialchars($post['title']) ?></td>
                        <td><?= htmlspecialchars($post['slug']) ?></td>
                        <td>
                            <img src="../upload/<?= htmlspecialchars($post['image_path']) ?>" alt="Görsel" width="60" class="img-thumbnail">
                        </td>
                        <td><?= date('d.m.Y H:i', strtotime($post['created_at'])) ?></td>
                        <td>
                            <a href="blog_edit.php?id=<?= $post['id'] ?>&lang=<?= $lang ?>" class="btn btn-sm btn-primary">✏️ Düzenle</a>
                            <a href="blog_delete.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğine emin misin?')">🗑️ Sil</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>

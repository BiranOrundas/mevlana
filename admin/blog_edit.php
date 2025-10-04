<?php
require_once 'config.php';
require_once 'auth.php';

$postId = $_GET['id'] ?? null;
if (!$postId) {
    header("Location: blog_list.php");
    exit;
}

$langs = ['tr', 'en', 'ar'];

// POST ile güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $slug = $_POST['slug'];
    $is_published = isset($_POST['is_published']) ? 1 : 0;

    // Mevcut görsel
    $currentImage = $_POST['current_image'];

if (!empty($_FILES['image']['name'])) {
    $targetDir = "../upload/";
    $imagePath = time() . '_' . basename($_FILES["image"]["name"]);
    $targetFile = $targetDir . $imagePath;

    // Uzantı kontrolü (opsiyonel)
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    $fileType = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
    if (!in_array($fileType, $allowedTypes)) {
        echo "Sadece JPG, PNG ve GIF dosyalarına izin veriliyor.";
        exit;
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        // Başarılı
    } else {
        echo "Dosya yüklenirken hata oluştu!";
        exit;
    }
}

    // posts tablosunu güncelle
    $stmt = $pdo->prepare("UPDATE posts SET slug = ?, image_path = ?, is_published = ? WHERE id = ?");
    $stmt->execute([$slug, $imagePath, $is_published, $postId]);

    // post_translations güncelle
    foreach ($langs as $lang) {
        $title = $_POST["title_$lang"];
        $content = $_POST["content_$lang"];
        $meta_title = $_POST["meta_title_$lang"];
        $meta_desc = $_POST["meta_desc_$lang"];
        $keywords = $_POST["keywords_$lang"];

        // Var mı kontrol et
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM post_translations WHERE post_id = ? AND lang_code = ?");
        $stmt->execute([$postId, $lang]);
        $exists = $stmt->fetchColumn();

        if ($exists) {
            // Güncelle
            $stmt = $pdo->prepare("UPDATE post_translations SET title = ?, content = ?, meta_title = ?, meta_description = ?, keywords = ? WHERE post_id = ? AND lang_code = ?");
            $stmt->execute([$title, $content, $meta_title, $meta_desc, $keywords, $postId, $lang]);
        } else {
            // Yeni ekle
            $stmt = $pdo->prepare("INSERT INTO post_translations (post_id, lang_code, title, content, meta_title, meta_description, keywords)
                                   VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$postId, $lang, $title, $content, $meta_title, $meta_desc, $keywords]);
        }
    }

    header("Location: blog_list.php");
    exit;
}

// GET ile mevcut verileri çek
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo "Blog bulunamadı.";
    exit;
}

// Tercümeleri çek
$translations = [];
$stmt = $pdo->prepare("SELECT * FROM post_translations WHERE post_id = ?");
$stmt->execute([$postId]);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $translations[$row['lang_code']] = $row;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>Blog Düzenle - <?= htmlspecialchars($post['slug']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">✏️ Blog Düzenle</h2>

    <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="slug" class="form-label">Slug (URL)</label>
            <input type="text" class="form-control" id="slug" name="slug" value="<?= htmlspecialchars($post['slug']) ?>" required>
            <div class="invalid-feedback">Lütfen slug giriniz.</div>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Görsel</label><br>
            <?php if ($post['image_path']): ?>
                <img src="../upload/<?= htmlspecialchars($post['image_path']) ?>" alt="Blog Görseli" style="max-width:150px; margin-bottom:10px;">
            <?php else: ?>
                <p>Görsel yüklenmemiş.</p>
            <?php endif; ?>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
            <input type="hidden" name="current_image" value="<?= htmlspecialchars($post['image_path']) ?>">
        </div>

        <div class="form-check form-switch mb-4">
            <input class="form-check-input" type="checkbox" id="is_published" name="is_published" <?= $post['is_published'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="is_published">Yayında mı?</label>
        </div>

        <ul class="nav nav-tabs" id="langTabs" role="tablist">
            <?php foreach ($langs as $i => $lang): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $i === 0 ? 'active' : '' ?>" id="<?= $lang ?>-tab" data-bs-toggle="tab" data-bs-target="#<?= $lang ?>" type="button" role="tab" aria-controls="<?= $lang ?>" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>">
                        <?= ['tr'=>'Türkçe','en'=>'English','ar'=>'Arabic'][$lang] ?>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="tab-content border border-top-0 p-4" id="langTabsContent" style="background:#f8f9fa;">
            <?php foreach ($langs as $i => $lang): 
                $t = $translations[$lang] ?? ['title'=>'', 'content'=>'', 'meta_title'=>'', 'meta_description'=>'', 'keywords'=>''];
            ?>
                <div class="tab-pane fade <?= $i === 0 ? 'show active' : '' ?>" id="<?= $lang ?>" role="tabpanel" aria-labelledby="<?= $lang ?>-tab">
                    <div class="mb-3">
                        <label for="title_<?= $lang ?>" class="form-label">Başlık (<?= ['tr'=>'Türkçe','en'=>'English','ar'=>'Arabic'][$lang] ?>)</label>
                        <input type="text" class="form-control" id="title_<?= $lang ?>" name="title_<?= $lang ?>" value="<?= htmlspecialchars($t['title']) ?>" required>
                        <div class="invalid-feedback">Lütfen başlık giriniz.</div>
                    </div>
                    <div class="mb-3">
                        <label for="content_<?= $lang ?>" class="form-label">İçerik (<?= ['tr'=>'Türkçe','en'=>'English','ar'=>'Arabic'][$lang] ?>)</label>
                        <textarea class="form-control" id="content_<?= $lang ?>" name="content_<?= $lang ?>" rows="5"><?= htmlspecialchars($t['content']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="meta_title_<?= $lang ?>" class="form-label">Meta Title (<?= ['tr'=>'Türkçe','en'=>'English','ar'=>'Arabic'][$lang] ?>)</label>
                        <input type="text" class="form-control" id="meta_title_<?= $lang ?>" name="meta_title_<?= $lang ?>" value="<?= htmlspecialchars($t['meta_title']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="meta_desc_<?= $lang ?>" class="form-label">Meta Açıklama (<?= ['tr'=>'Türkçe','en'=>'English','ar'=>'Arabic'][$lang] ?>)</label>
                        <textarea class="form-control" id="meta_desc_<?= $lang ?>" name="meta_desc_<?= $lang ?>" rows="2"><?= htmlspecialchars($t['meta_description']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="keywords_<?= $lang ?>" class="form-label">Anahtar Kelimeler (<?= ['tr'=>'Türkçe','en'=>'English','ar'=>'Arabic'][$lang] ?>)</label>
                        <input type="text" class="form-control" id="keywords_<?= $lang ?>" name="keywords_<?= $lang ?>" value="<?= htmlspecialchars($t['keywords']) ?>">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Güncelle</button>
        <a href="blog_list.php" class="btn btn-secondary mt-3 ms-2">İptal</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Bootstrap validation
(() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>

</body>
</html>

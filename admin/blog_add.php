<?php
require_once 'config.php';
require_once 'auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $slug = $_POST['slug'];
    $is_published = isset($_POST['is_published']) ? 1 : 0;
    $langs = ['tr', 'en', 'ar'];
    $imagePath = '';

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

    // posts tablosuna kayıt
    $stmt = $pdo->prepare("INSERT INTO posts (slug, image_path, is_published) VALUES (?, ?, ?)");
    $stmt->execute([$slug, $imagePath, $is_published]);
    $postId = $pdo->lastInsertId();

    // post_translations kayıtları
    foreach ($langs as $lang) {
        $title = $_POST["title_$lang"];
        $content = $_POST["content_$lang"];
        $meta_title = $_POST["meta_title_$lang"];
        $meta_desc = $_POST["meta_desc_$lang"];
        $keywords = $_POST["keywords_$lang"];

        $stmt = $pdo->prepare("INSERT INTO post_translations (post_id, lang_code, title, content, meta_title, meta_description, keywords)
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$postId, $lang, $title, $content, $meta_title, $meta_desc, $keywords]);
    }

    header("Location: blog_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>Yeni Blog Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">➕ Yeni Blog Ekle</h2>

    <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="slug" class="form-label">Slug (URL)</label>
            <input type="text" class="form-control" id="slug" name="slug" required>
            <div class="invalid-feedback">Lütfen slug giriniz.</div>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Görsel</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
        </div>

        <div class="form-check form-switch mb-4">
            <input class="form-check-input" type="checkbox" id="is_published" name="is_published" checked>
            <label class="form-check-label" for="is_published">Yayında mı?</label>
        </div>

        <ul class="nav nav-tabs" id="langTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tr-tab" data-bs-toggle="tab" data-bs-target="#tr" type="button" role="tab" aria-controls="tr" aria-selected="true">Türkçe</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="en-tab" data-bs-toggle="tab" data-bs-target="#en" type="button" role="tab" aria-controls="en" aria-selected="false">English</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="ar-tab" data-bs-toggle="tab" data-bs-target="#ar" type="button" role="tab" aria-controls="ar" aria-selected="false">Arabic</button>
            </li>
        </ul>
        <div class="tab-content border border-top-0 p-4" id="langTabsContent" style="background:#f8f9fa;">
            <?php foreach (['tr' => 'Türkçe', 'en' => 'English', 'ar' => 'Arabic'] as $lang => $label): ?>
                <div class="tab-pane fade <?= $lang === 'tr' ? 'show active' : '' ?>" id="<?= $lang ?>" role="tabpanel" aria-labelledby="<?= $lang ?>-tab">
                    <div class="mb-3">
                        <label for="title_<?= $lang ?>" class="form-label">Başlık (<?= $label ?>)</label>
                        <input type="text" class="form-control" id="title_<?= $lang ?>" name="title_<?= $lang ?>" required>
                        <div class="invalid-feedback">Lütfen başlık giriniz.</div>
                    </div>
                    <div class="mb-3">
                        <label for="content_<?= $lang ?>" class="form-label">İçerik (<?= $label ?>)</label>
                        <textarea class="form-control" id="content_<?= $lang ?>" name="content_<?= $lang ?>" rows="5"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="meta_title_<?= $lang ?>" class="form-label">Meta Title (<?= $label ?>)</label>
                        <input type="text" class="form-control" id="meta_title_<?= $lang ?>" name="meta_title_<?= $lang ?>">
                    </div>
                    <div class="mb-3">
                        <label for="meta_desc_<?= $lang ?>" class="form-label">Meta Açıklama (<?= $label ?>)</label>
                        <textarea class="form-control" id="meta_desc_<?= $lang ?>" name="meta_desc_<?= $lang ?>" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="keywords_<?= $lang ?>" class="form-label">Anahtar Kelimeler (<?= $label ?>)</label>
                        <input type="text" class="form-control" id="keywords_<?= $lang ?>" name="keywords_<?= $lang ?>">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Kaydet</button>
        <a href="blog_list.php" class="btn btn-secondary mt-3 ms-2">İptal</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Bootstrap 5 validation
(() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})();
</script>

</body>
</html>

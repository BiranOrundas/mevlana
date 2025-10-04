<?php
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-card {
            margin-top: 100px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Admin Paneli</a>
    <div class="d-flex">
      <span class="navbar-text text-white me-3">
        Hoş geldin, <?= htmlspecialchars($_SESSION['admin_username']) ?>
      </span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Çıkış Yap</a>
    </div>
  </div>
</nav>

<div class="container dashboard-card">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h3 class="card-title mb-4">Yönetim Seçenekleri</h3>
                    <a href="blog_list.php" class="btn btn-primary btn-lg w-100 mb-3">📝 Blogları Yönet</a>
                    <a href="logout.php" class="btn btn-danger btn-lg w-100">🚪 Çıkış Yap</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

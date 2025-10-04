<?php
require_once '../admin/config.php';  // PDO bağlantısı burada olmalı

$perPage = 6;
$page = isset($_GET['page']) && intval($_GET['page']) > 0 ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

// Dil parametresi (default tr)
$lang = $_GET['lang'] ?? 'tr';
$lang = in_array($lang, ['tr', 'en', 'ar']) ? $lang : 'tr';

// Toplam yayınlanmış gönderi sayısını alıyoruz
$totalStmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE is_published = 1");
$totalStmt->execute();
$totalPosts = $totalStmt->fetchColumn();
$totalPages = ceil($totalPosts / $perPage);

// Gönderileri çekiyoruz (join ile uygun dilde başlık ve içerik)
$stmt = $pdo->prepare("
    SELECT p.id, p.slug, p.image_path, p.created_at, pt.title, pt.content 
    FROM posts p
    INNER JOIN post_translations pt ON p.id = pt.post_id AND pt.lang_code = :lang
    WHERE p.is_published = 1
    ORDER BY p.created_at DESC
    LIMIT :limit OFFSET :offset
");

$stmt->bindValue(':lang', $lang, PDO::PARAM_STR);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Mevlana Bazaar | Güncel Paylaşımlar.</title>

    <link rel="icon" href="../favicon.ico" type="image/x-icon">

    <!-- Open Graph -->
<meta property="og:title" content="Mevlana Bazaar | Güncel Blog Yazıları">
<meta property="og:description" content="Mevlana Bazaar blog sayfasında; Türk mutfağı, lokum, kahve, çay ve kültürel hediyeliklerle ilgili yazılara ulaşın.">
<meta property="og:url" content="https://www.mevlanabazaar.com/tr/blog_module.php?lang=tr">
<meta property="og:type" content="website">
<meta property="og:image" content="https://www.mevlanabazaar.com/images/logo.png">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Mevlana Bazaar | Güncel Blog Yazıları">
<meta name="twitter:description" content="Mevlana Bazaar blog sayfasında; Türk mutfağı, lokum, kahve, çay ve kültürel hediyeliklerle ilgili yazılara ulaşın.">
<meta name="twitter:image" content="https://www.mevlanabazaar.com/images/logo.png">

<!-- Schema.org (JSON-LD) -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Blog",
  "name": "Mevlana Bazaar | Güncel Blog Yazıları",
  "url": "https://www.mevlanabazaar.com/blog_module.php?lang=tr",
  "description": "Mevlana Bazaar blog sayfasında; Türk mutfağı, lokum, kahve, çay ve kültürel hediyeliklerle ilgili yazılara ulaşın.",
  "publisher": {
    "@type": "Organization",
    "name": "Mevlana Bazaar",
    "logo": {
      "@type": "ImageObject",
      "url": "https://www.mevlanabazaar.com/images/logo.png"
    }
  },
  "image": "https://www.mevlanabazaar.com/images/og-image.jpg"
}
</script>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">	
	<link rel="stylesheet" href="../css/owl.carousel.css">	
	<link rel="stylesheet" href="../css/bootstrap.min.css">	
	<link rel="stylesheet" href="../css/font-awesome.min.css">
	<link rel="stylesheet" href="../css/animate.min.css">
	<link rel="stylesheet" href="../css/main.css">	
	<link rel="stylesheet" href="../css/responsive.css">
	
	<!-- Js -->
	<script src="../js/vendor/modernizr-2.6.2.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>	
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>	
	<script src="../js/jquery.nav.js"></script>
	<script src="../js/jquery.sticky.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/wow.min.js"></script>
	<script src="../js/main.js"></script>

    <script>
			$(document).ready(function(){
		$(".owl-carousel").owlCarousel({
			items: 1,
			loop: true,
			autoplay: true
		});
		});

		$('.navbar-nav a').click(function(){
    $(".navbar-collapse").collapse('hide');
});

	</script>
	 <meta name="google-site-verification" content="DHufTRNHsyUi3qvM6Ed8b3LCTrtN0i7hF-eNwr46jxc" />
	
	<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-24JELY2TNS"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-24JELY2TNS');
</script>

	<style>
		.a {
			text-decoration: none;
			color: black;
transition-duration: 0.5s !important;
		}
		.a:hover{
			font-size: 15.1px;
			transition-duration: 0.3s !important;
		}

		.banner{
			margin: auto;
			text-align: center;
			background-color: #9a7d1f;
			color: rgb(255, 255, 255);
			font-size: larger;
			letter-spacing: 2px;
			padding: 3px;
			text-shadow: 1px 2px 5px  #00000086;
		}

.urunler {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.urun {
    width: 300px;
    border: 1px solid #ccc;
    padding: 5px;
    border-radius: 8px;
    background: #fafafa;
}
.urun img {
    max-width: 100%;
    height: auto;
}
.whatsapp-button {
  position: fixed;
  bottom: 20px;
  right: 20px;
  z-index: 9999;
  background-color: #25D366;
  border-radius: 50%;
  padding: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
  transition: transform 0.3s ease;
  transform: translateZ(0); /* GPU acceleration */
  will-change: transform;
}

.whatsapp-button:hover {
  transform: scale(1.1);
}

.whatsapp-button img {
  width: 40px;
  height: 40px;
}

@media (max-width: 576px) {
  .whatsapp-button {
    bottom: 35px;
    right: 15px;
    padding: 8px;
  }

  .whatsapp-button img {
    width: 36px;
    height: 36px;
  }

  .whatsapp-tooltip {
  position: fixed;
  bottom: 80px; /* butonun üstünde */
  right: 20px;
  background: #fff;
  color: #333;
  padding: 8px 12px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.2);
  z-index: 9999;
  font-size: 14px;
  max-width: 200px;
  opacity: 0;
  transform: translateY(10px);
  transition: opacity 0.4s ease, transform 0.4s ease;
  pointer-events: none;
}

.whatsapp-tooltip.show {
  opacity: 1;
  transform: translateY(0);
  pointer-events: auto;
}



}
.thumbnail {
    border: 1px solid #9999998f;
    box-shadow: 0 4px 8px rgba(0,0,0,0.5);
    border-radius: 10px;
    padding: 10px;
    height: 400px;
    display: flex;
    flex-direction: column;    
   
}

.thumbnail .caption {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.thumbnail h5 {
    margin-top: 10px; /* istersen biraz boşluk bırak */
    margin-bottom: 5px; /* alt boşluk çoksa azalt */
}

.thumbnail p {
    margin-top: 0; /* varsa boşlukları azalt */
    margin-bottom: 10px;
}

	</style>

</head>
<body>

    	<!--
	header-img start 
	============================== -->
	<div class="banner" > Tüm ürünlerde %20 indirim! </div>
	<section id="hero-area">
		<img class="img-responsive" src="../images/main/headerBG.jpg" alt="mevlana_bazaar" style="object-fit: cover; object-position: center 30%; width: 100%; max-height: 650px !important; filter: saturate(1.1);">
	</section>
	<!--
    Header start 
	============================== --> 
	<nav id="navigation">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="block">
						<nav class="navbar navbar-default">
							<div class="container-fluid">
								<!-- Brand and toggle get grouped for better mobile display -->
								<div class="navbar-header">
									<button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
										data-target="#bs-example-navbar-collapse-1">
										<span class="sr-only">Toggle navigation</span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
									</button>
									<a class="navbar-brand" href="#">
										<img src="../images/logo.png" alt="Logo">
									</a>

								</div>

								<!-- Collect the nav links, forms, and other content for toggling -->
								<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
									<ul class="nav navbar-nav navbar-right" id="top-nav">
										<li><a href="#hero-area">Anasayfa</a></li>
										<li><a href="#about-us">Hakkımızda</a></li>
										<li><a href="blog_module.php">Yazılar</a></li>
										<li><a href="index.php#products">Ürünler</a></li>										
										<li><a href="#contact-us">Bize Ulaşın</a></li>
									</ul>
								</div>
							</div>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</nav>		
<div class="container" style="margin-top: 50px;">
    <h1 class="text-center" style="margin-bottom: 30px;">
        <?= $lang === 'tr' ? 'Güncel Paylaşımlar' : ($lang === 'en' ? 'Latest Posts' : 'أحدث المشاركات') ?>
    </h1>

    <div class="row">
        <?php if (!$posts): ?>
            <div class="col-md-12">
                <p class="text-center">
                    <?= $lang === 'tr' ? 'Blog bulunamadı.' : ($lang === 'en' ? 'No blogs found.' : 'لم يتم العثور على مدونات.') ?>
                </p>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="col-xs-6 col-sm-6 col-md-3">
                    <div class="thumbnail" >
                        <img src="../upload/<?= htmlspecialchars($post['image_path']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" style="height: 200px; object-fit: cover; width: 100%;">
                        <div class="caption">
                            <p class="text-muted" style="font-size:10px; margin: 0;">mevlana Bazaar</p>
                            <h5 style="font-weight: bold; font-size: 16px;"><?= htmlspecialchars($post['title']) ?></h5>
                           
                            <p>
                                <a href="./blog_detail.php?slug=<?= urlencode($post['slug']) ?>&lang=<?= $lang ?>" class="btn btn-warning btn-md ">
                                    <?= $lang === 'tr' ? 'Devamını Oku' : ($lang === 'en' ? 'Read More' : 'اقرأ المزيد') ?>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <nav aria-label="Sayfalama" style="margin-top: 30px;">
            <ul class="pagination text-center">
                <?php if ($page > 1): ?>
                    <li>
                        <a href="?lang=<?= $lang ?>&page=<?= $page - 1 ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo; <?= $lang === 'tr' ? 'Önceki' : ($lang === 'en' ? 'Previous' : 'السابق') ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                    <li class="<?= $p == $page ? 'active' : '' ?>">
                        <a href="?lang=<?= $lang ?>&page=<?= $p ?>"><?= $p ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <li>
                        <a href="?lang=<?= $lang ?>&page=<?= $page + 1 ?>" aria-label="Next">
                            <span aria-hidden="true"><?= $lang === 'tr' ? 'Sonraki' : ($lang === 'en' ? 'Next' : 'التالي') ?> &raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



</body>
</html>

<?php
require_once '../admin/config.php';  

$perPage = 6;
$page = isset($_GET['page']) && intval($_GET['page']) > 0 ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $perPage;


$lang = $_GET['lang'] ?? 'en';
$lang = in_array($lang, ['tr', 'en', 'ar']) ? $lang : 'en';

// Toplam 
$totalStmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE is_published = 1");
$totalStmt->execute();
$totalPosts = $totalStmt->fetchColumn();
$totalPages = ceil($totalPosts / $perPage);

// Gönderiler
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
<meta property="og:title" content="Mevlana Bazaar | Latest Blog Posts">
<meta property="og:description" content="Read the latest blogs on Turkish delights, coffee, tea, and cultural gifts at Mevlana Bazaar.">
<meta property="og:url" content="https://www.mevlanabazaar.com/blog_module.php?lang=en">
<meta property="og:type" content="website">
<meta property="og:image" content="https://www.mevlanabazaar.com/images/og-image.jpg">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Mevlana Bazaar | Latest Blog Posts">
<meta name="twitter:description" content="Read the latest blogs on Turkish delights, coffee, tea, and cultural gifts at Mevlana Bazaar.">
<meta name="twitter:image" content="https://www.mevlanabazaar.com/images/og-image.jpg">

<!-- Schema.org (JSON-LD) -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Blog",
  "name": "Mevlana Bazaar | Latest Blog Posts",
  "url": "https://www.mevlanabazaar.com/blog_module.php?lang=en",
  "description": "Read the latest blogs on Turkish delights, coffee, tea, and cultural gifts at Mevlana Bazaar.",
  "publisher": {
    "@type": "Organization",
    "name": "Mevlana Bazaar",
    "logo": {
      "@type": "ImageObject",
      "url": "https://www.mevlanabazaar.com/images/logo.png"
    }
  },
  "image": "https://www.mevlanabazaar.com/images/logo.png"
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
									<li><a href="index.php">Home</a></li>
										<li><a href="#about-us">About Us</a></li>
										<li><a href="blog_module.php">Blogs</a></li>
										<li><a href="index.php#products">Products</a></li>									
										<li><a href="#contact-us">Contact Us</a></li>
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
                    <div class="thumbnail">
                        <img src="../upload/<?= htmlspecialchars($post['image_path']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" style="height: 200px; object-fit: cover; width: 100%;">
                        <div class="caption">
                            <p class="text-muted" style="font-size:10px; margin: 0;">mevlana Bazaar</p>
                            <h5 style="font-weight: bold; font-size: 16px;"  ><?= htmlspecialchars($post['title']) ?></h5>
                          
                            <p>
                                <a href="./blog_detail.php?slug=<?= urlencode($post['slug']) ?>&lang=<?= $lang ?>" class="btn btn-warning btn-md" style="margin-top: 8px;">
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
</div>
<section id="footer">
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<div class="block wow fadeInLeft" data-wow-delay="200ms">
						<h3>CONTACT <span>INFO</span></h3>
						<div class="info">
							<ul>
								<li>
									<h4><i class="fa fa-phone"></i>Phone:</h4>
									<p> <a class="a" href="tel:+02124582427"> 0 212 458 24 27 </p>
									
									<p> <a class="a" href="tel:+05558275555"> +90 555 827 55 55 </p>

								</li>
								<li>
									<h4><i class="fa fa-map-marker"></i>Adress: </h4>
									<p><a class="a" href="https://maps.app.goo.gl/89wjkRkvtECroiS2A" target="_blank" rel="noopener noreferrer"> Sahil Yolu, Sultanahmet Mahallesi, Kennedy Cad. No:38, 34400 Fatih/İstanbul</a></p>
								</li>
								<li>
									<h4><i class="fa fa-envelope"></i>E-mail:</h4>
									<p><a class="a" href="mailto:mevlanabazaar@gmail.com">mevlanabazaar@gmail.com</a></p>

								</li>
							</ul>
						</div>
					</div>
				</div>
				<!-- .col-md-4 close -->
				<div class="col-md-4">
					<div class="block wow fadeInLeft" data-wow-delay="700ms">
						<h3>LASTEST <span>NEWS</span></h3>
						<div class="blog">
							<ul>
								<li>
									<h4><a href="https://www.turizmaktuel.com/haber/mevlana-bazaar-sektorun-lideri-oldu">Mevlana Bazaar became the leader of the industry.</a></h4>
									<p>Mevlana Bazaar CEO Abdulvahap Polat gave information about his company in an interview with Turizm Aktüel at the IMEX Fair.</p>
								</li>
								<li>
									<h4><a href="https://mevlana-bazaar.best-istanbul-hotels.com/tr/">Hotel Mevlana Bazaar Istanbul</a></h4>
									<p>Offering 24-hour security and currency exchange, Hotel Mevlana Bazaar is located in Istanbul. Beyazıt Tower is a 25-minute walk away, and Sirkeci train station is a 10-minute walk away.</p>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!-- .col-md-4 close -->
				<div class="col-md-4">
					<div class="block wow fadeInLeft" data-wow-delay="1100ms">
						<div class="gallary">
							<h3>WE ON <span>SOCIAL</span> MEDIA</h3>
							<ul>
								<li>
									<a href="https://www.instagram.com/mevlanabazaar"><img style="max-width:64px"  src="https://cdn.shopier.app/pictures_large/mevlanapolatbazaar_d8a96ce1dc5cab45901a56dc20f625d9.jpeg" alt=""></a>
								</li>
								<li>
									<a href="https://www.instagram.com/mevlanabazaar"><img style="max-width:64px"  src="https://cdn.shopier.app/pictures_large/mevlanapolatbazaar_31310a13082f6f0d3e0b05b4940abe38.png" alt=""></a>
								</li>
								<li>
									<a href="https://www.instagram.com/mevlanabazaar"><img style="max-width:64px"  src="https://cdn.shopier.app/pictures_large/mevlanapolatbazaar_50f3f035843fbf8fa753844212be289a.jpg" alt=""></a>
								</li>
								<li>
									<a href="https://www.instagram.com/mevlanabazaar"><img style="max-width:64px"  src="https://cdn.shopier.app/pictures_large/mevlanapolatbazaar_de321ec06a42be211329e96f73f70565.jpg" alt=""></a>
								</li>
							</ul>
						</div>
						<div class="social-media-link">
							<h3>Follow <span> us</span></h3>
							<ul>
								<li>
									<a href="#">
										<i class="fa fa-twitter"></i>
									</a>
								</li>
								<li>
									<a href="https://www.facebook.com/Mevlanabazaar/?locale=tr_TR">
										<i class="fa fa-facebook"></i>
									</a>
								</li>
								<li>
									<a href="https://www.instagram.com/mevlanabazaar/">
										<i class="fa fa-instagram"></i>
									</a>
								</li>
								<li>
									<a href="#">
										<i class="fa fa-youtube"></i>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!-- .col-md-4 close -->
			</div><!-- .row close -->
		</div><!-- .containe close -->
	</section><!-- #footer close -->
	<!--
    footer-bottom  start
    ============================= -->
	<footer id="footer-bottom">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-sm-12">
					<div class="block">
						<p>Copyright &copy; 2025 - All Rights Reserved.Design and Developed By <a
								href="http://www.mevlanabazaar.com">mevlanabazaar</a></p>
					</div>
				</div>
			</div>
		</div>
	</footer>



</body>
</html>

<?php
    $title = html_entity_decode($urun['title'], ENT_QUOTES, 'UTF-8');
    $desc = kisalt($urun['description']);
    $price = $urun['priceData']['discountedPrice'] ?? $urun['priceData']['price'];
    $currency = $urun['priceData']['currency'];
    $stock = $urun['stockQuantity'];
    $url = $urun['url'];
    $image = (!empty($urun['media']) && isset($urun['media'][0]['url'])) ? $urun['media'][0]['url'] : 'default.jpg';
    $category = $urun['categories'][0]['title'] ?? 'Kategori Yok';

    // Fiyat hesaplama
    if ($lang === 'en' || $lang === 'ar') {
        $priceUSD = $price / $rate;
        $displayPrice = number_format($priceUSD, 2, '.', '') . ' $';
    } else {
        $displayPrice = number_format($price, 2, ',', '.') . ' ₺';
    }
?>

<div class="col-xs-12 col-sm-6 col-md-3 shadow-sm">
    <div class="panel panel-default transition curs">
        <div class="panel-body text-center row mt-2">
            <a href="<?= $url ?>" target="_blank">
                <img src="<?= $image ?>" loading="lazy" alt="<?= htmlspecialchars($title) ?>" class="img-responsive center-block" style="max-height:400px; margin-bottom: 10px; border-radius:7px;">
            </a>
            <h3 style="font-size: 14px; background-color:#fff9e5ff; color: #9a7d1f; font-weight: 600; height: 60px; padding-top:20px;">
                <?= htmlspecialchars($title) ?>
            </h3>
            <h3 style="font-size: 10px; color: #a3a096ff; font-weight: 300; min-height: 15px; margin-top:5px;">
                <?= htmlspecialchars($category) ?>
            </h3>
            <div class="text-center">  					
                <p style="font-size:20px; margin-top:5px; font-weight:bold;">
                    <?= $displayPrice; ?>
                </p> 
                <p class="stock-info" data-stock="<?= $stock ?>">
                    <?= $stockLabel ?>: <?= $stock ?>
                </p>
            </div>	
        </div>
        <div class="panel-footer text-center">
            <a href="<?= $url ?>" target="_blank" class="btn btn-sm" style="background: #dab22fff; color: #fff; border: none; text-transform: uppercase; font-weight: 600;">
                <?= $buttonText ?>
            </a>
        </div>
    </div>
</div>

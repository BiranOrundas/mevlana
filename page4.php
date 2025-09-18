<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();


$totalPages = 4;
$limit = 50; 
$allProducts = [];

for ($page = 1; $page <= $totalPages; $page++) {
    // API isteği için URL oluştur
    $url = "https://api.shopier.com/v1/products?limit=$limit&page=$page&sort=dateDesc";

    try {
        // API'yi çağır
        $response = $client->request('GET', $url, [
            'headers' => [
                'accept' => 'application/json',
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI2MTQ2N2Q4YjQ1OTk0ZTRmN2ZjMDI5NTM2ZDFhMDg5ZSIsImp0aSI6IjQxZmI0YjExNzRmNzI3ZjFlM2NjMjVlNzBiZjY3MTdkNTM1YzhmYWQxZWM2NjFjOWI5MWZkZGQzZjRkZDQ0NTMxZWM3NGNiN2MwOTcwZDcxMTUyMTNiMjljNjk3MDU1YzE5MjYwM2ZhOTdiZTlkNWI2ZTAzZjg4NjNmNjhlZDQyN2JkZmM5MzMzNDYwYTgxYjY2YzZhYmNmMjBkOWMzN2QiLCJpYXQiOjE3NTc3NTUyNjIsIm5iZiI6MTc1Nzc1NTI2MiwiZXhwIjoxOTE1NTQwMDIyLCJzdWIiOiI4ODM0NTAiLCJzY29wZXMiOlsib3JkZXJzOnJlYWQiLCJvcmRlcnM6d3JpdGUiLCJwcm9kdWN0czpyZWFkIiwicHJvZHVjdHM6d3JpdGUiLCJzaGlwcGluZ3M6cmVhZCIsInNoaXBwaW5nczp3cml0ZSIsImRpc2NvdW50czpyZWFkIiwiZGlzY291bnRzOndyaXRlIiwicGF5b3V0czpyZWFkIiwicmVmdW5kczpyZWFkIiwicmVmdW5kczp3cml0ZSIsInNob3A6cmVhZCIsInNob3A6d3JpdGUiXX0.IumIWKDP0CkMTgMAAMoBr8fgKaNNLja-bal24FI-B_qkq8G-UvQRyAXoQg6kHjTrhBfzoygnZ0QJmTlzsgt9uA3JGGBDfi_d0tRPpF4hoI4M7hn-C_w6OAB7ZUxqVGWwkkFPjt_Bo7f8T7WGv6x3mK5MPbmQl_AXGyYegOMEaaXyOx_XfesLqFXEIeZ6mNdjgEoKnbsMuTCKueY6A57mSJtohsan0vuJdz-1Fi8ncJT01TFYyRhsDDsr4jwVjHoTkRGilh2EtGnKdNsHIbegN9JqMgHvPjFCXuXzJBx6DABX2zfF5u_nqESSeis7cSssoUlBWxTog8yhZgdBH5tBrA',
            ],
        ]);

        // API yanıtından veri al
        $body = $response->getBody();
        $data = json_decode($body, true);

        if (isset($data['data'])) {
            $allProducts = array_merge($allProducts, $data['data']);
        }
    } catch (\GuzzleHttp\Exception\RequestException $e) {
        // Hata durumunda mesaj yazdır
        echo "API isteği başarısız oldu: " . $e->getMessage() . "\n";
    }
}

$path = $_SERVER['REQUEST_URI'];
// Dil tespiti

if (strpos($path, '/en/') !== false) {
    $lang = 'en';
} elseif (strpos($path, '/ar/') !== false) {
    $lang = 'ar';
} elseif (strpos($path, '/tr/') !== false) {
    $lang = 'tr';
} else {
    $lang = 'tr'; 
}

$translations = [
    'tr' => [
        'button' => 'Ürünü Gör',
        'stock' => 'Stok',
    ],
    'en' => [
        'button' => 'Shop Now',
        'stock' => 'Stock',
    ],
    'ar' => [
        'button' => 'عرض المنتج',
        'stock' => 'المخزون',
    ],
];

$buttonText = $translations[$lang]['button'] ?? 'Ürünü Gör';
$stockLabel = $translations[$lang]['stock'] ?? 'Stok';

// Filtrelenecek kategoriler
$allowedCategories = ['KAHVELER','BAHARAT','KURUYEMIS','KURUMEYVE','CAYLAR','ÇIKOLATA','LOKUM'];
$allowedCategories = array_map('mb_strtolower', $allowedCategories);

// Kategoriye göre filtrele
$filteredData = array_filter($data, function($urun) use ($allowedCategories) {
    $category = $urun['categories'][0]['title'] ?? '';
    return in_array(mb_strtolower($category), $allowedCategories);
});

// HTML çıktı
?> 

<section id="price" style="margin-top:-140px;">
    <div class="container" id="urunler">
        <div class="row">
            <?php foreach ($filteredData as $urun): 
                $title = $urun['title'];
                $desc = kisalt($urun['description']);
                $price = $urun['priceData']['discountedPrice'] ?? $urun['priceData']['price'];
                $currency = $urun['priceData']['currency'];
                $stock = $urun['stockQuantity'];
                $url = $urun['url'];
                $image = $urun['media'][0]['url'] ?? 'default.jpg';
                $category = $urun['categories'][0]['title'] ?? 'Kategori Yok';
            ?>
            <div class="col-sm-6 col-md-3 shadow-sm">
                <div class="panel panel-default transition curs">
                    <div class="panel-body text-center row">
                        <a href="<?= $url ?>" target="_blank">
                            <img src="<?= $image ?>" alt="<?= htmlspecialchars($title) ?>" class="img-responsive center-block" style="max-height:200px; margin-bottom: 10px; border-radius:7px;">
                        </a>
                        <h3 style="font-size: 14px; background-color:#fff9e5ff; color: #9a7d1f; font-weight: 600; min-height: 60px; padding-top:20px;">
                            <?= htmlspecialchars($title) ?>
                        </h3>
                        <h3 style="font-size: 10px; color: #a3a096ff; font-weight: 300; min-height: 15px; margin-top:5px;">
                            <?= htmlspecialchars($category) ?>
                        </h3>
                        <div class="text-center">  					
                            <p style="font-size:20px; margin-top:5px; font-weight:bold;">
                                <?= $price . ' ₺ ' ?>
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
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Stok azsa kırmızı göster -->
<script>
function stocklimit () {
    const stockElements = document.querySelectorAll('.stock-info');

    stockElements.forEach(el => {
        const stock = parseInt(el.dataset.stock);
        if (stock <= 5) {
            el.style.color = "red";
        } else {
            el.style.color = "black";
        }
    });
}

stocklimit();
</script>




 
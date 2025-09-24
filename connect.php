<style>

    a {
    color: #9a7d1f  !important;
    text-decoration: none !important;
    font-weight: 600 !important;
    
}
</style>

<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();
$totalPages = 10;
$limit = 50; 
$allProducts = [];

for ($page = 1; $page <= $totalPages; $page++) {
    // API isteği için URL oluştur
    $url = "https://api.shopier.com/v1/products?limit=$limit&page=$page&sort=dateDesc";

    try {
        // API
        $response = $client->request('GET', $url, [
            'headers' => [
                'accept' => 'application/json',
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI2MTQ2N2Q4YjQ1OTk0ZTRmN2ZjMDI5NTM2ZDFhMDg5ZSIsImp0aSI6IjQxZmI0YjExNzRmNzI3ZjFlM2NjMjVlNzBiZjY3MTdkNTM1YzhmYWQxZWM2NjFjOWI5MWZkZGQzZjRkZDQ0NTMxZWM3NGNiN2MwOTcwZDcxMTUyMTNiMjljNjk3MDU1YzE5MjYwM2ZhOTdiZTlkNWI2ZTAzZjg4NjNmNjhlZDQyN2JkZmM5MzMzNDYwYTgxYjY2YzZhYmNmMjBkOWMzN2QiLCJpYXQiOjE3NTc3NTUyNjIsIm5iZiI6MTc1Nzc1NTI2MiwiZXhwIjoxOTE1NTQwMDIyLCJzdWIiOiI4ODM0NTAiLCJzY29wZXMiOlsib3JkZXJzOnJlYWQiLCJvcmRlcnM6d3JpdGUiLCJwcm9kdWN0czpyZWFkIiwicHJvZHVjdHM6d3JpdGUiLCJzaGlwcGluZ3M6cmVhZCIsInNoaXBwaW5nczp3cml0ZSIsImRpc2NvdW50czpyZWFkIiwiZGlzY291bnRzOndyaXRlIiwicGF5b3V0czpyZWFkIiwicmVmdW5kczpyZWFkIiwicmVmdW5kczp3cml0ZSIsInNob3A6cmVhZCIsInNob3A6d3JpdGUiXX0.IumIWKDP0CkMTgMAAMoBr8fgKaNNLja-bal24FI-B_qkq8G-UvQRyAXoQg6kHjTrhBfzoygnZ0QJmTlzsgt9uA3JGGBDfi_d0tRPpF4hoI4M7hn-C_w6OAB7ZUxqVGWwkkFPjt_Bo7f8T7WGv6x3mK5MPbmQl_AXGyYegOMEaaXyOx_XfesLqFXEIeZ6mNdjgEoKnbsMuTCKueY6A57mSJtohsan0vuJdz-1Fi8ncJT01TFYyRhsDDsr4jwVjHoTkRGilh2EtGnKdNsHIbegN9JqMgHvPjFCXuXzJBx6DABX2zfF5u_nqESSeis7cSssoUlBWxTog8yhZgdBH5tBrA',
            ],
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);

        // var_dump($data);
        //     echo '<pre class="contaier">';
        //     print_r($data);
        //     echo '</pre>';


        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "JSON Decode Hatası: " . json_last_error_msg() . PHP_EOL;
            break;
        }

        if (!empty($data)) {
            
            $allProducts = array_merge($allProducts, $data);
            if (count($data) < $limit) {
                // Son sayfaya geldik
                break;
            }
        } else {
            echo "API'den veri alınamadı veya veri boş." . PHP_EOL;
            break;
        }
    } catch (\Exception $e) {
        echo "API isteği sırasında hata: " . $e->getMessage() . PHP_EOL;
        break; // veya continue, ihtiyaca göre
    }
} 

// Toplam alınan ürünleri kontrol et


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
        'categories' => [
            'kahveler' => 'KAHVELER',
            'baharat' => 'BAHARAT',
            'kuruyemis' => 'KURUYEMİŞ',
            'kurumeyve' => 'KURUMEYVE',
            'caylar' => 'ÇAYLAR',
            'çikolata' => 'ÇİKOLATA',
            'lokum' => 'LOKUM',
            'zeytinyaglar' => 'ZEYTİNYAĞLAR',
            'valizler' => 'VALİZLER',
            'tabaklar' => 'TABAKLAR',
            'parfum-esanslar' => 'PARFÜM-ESANSLAR',
            'tum-urunler' => 'TÜM ÜRÜNLER'
        ]
    ],
    'en' => [
        'button' => 'Shop Now',
        'stock' => 'Stock',
        'categories' => [
            'kahveler' => 'Coffee',
            'baharat' => 'Spices',
            'kuruyemis' => 'Nuts',
            'kurumeyve' => 'Dried Fruits',
            'caylar' => 'Tea',
            'çikolata' => 'Chocolate',
            'lokum' => 'Turkish Delight',
            'zeytinyaglar' => 'Olive Oils',
            'valizler' => 'Suitcases',
            'tabaklar' => 'Plates',
            'parfum-esanslar' => 'Perfumes & Essences',
            'tum-urunler' => 'All Products'
        ]
    ],
    'ar' => [
        'button' => 'عرض المنتج',
        'stock' => 'المخزون',
        'categories' => [
            'kahveler' => 'قهوة',
            'baharat' => 'بهارات',
            'kuruyemis' => 'مكسرات',
            'kurumeyve' => 'فاكهة مجففة',
            'caylar' => 'شاي',
            'çikolata' => 'شوكولاتة',
            'lokum' => 'راحة الحلقوم',
            'zeytinyaglar' => 'زيوت الزيتون',
            'valizler' => 'حقائب',
            'tabaklar' => 'أطباق',
            'parfum-esanslar' => 'عطور و اسانس',
            'tum-urunler' => 'كل المنتجات'
        ]
    ]
];



$buttonText = $translations[$lang]['button'] ?? 'Ürünü Gör';
$stockLabel = $translations[$lang]['stock'] ?? 'Stok';
$rawCategory = $urun['categories'][0]['title'] ?? 'Tüm Ürünler';
$category = $translations[$lang]['categories'][$rawCategory] ?? $rawCategory;

// Açıklama kısaltma fonksiyonu
function kisalt($metin, $kelimeSayisi = 3) {
    $metin = preg_replace('/<!--[\s\S]*?-->/', '', $metin);
    $metin = strip_tags($metin);
    $kelimeler = explode(' ', $metin);
    $kisaMetin = array_slice($kelimeler, 0, $kelimeSayisi);
    return implode(' ', $kisaMetin) . '...';
}

// Filtrelenecek kategoriler
$allowedCategories = ['KAHVELER','BAHARAT','KURUYEMIS','KURUMEYVE','CAYLAR','ÇIKOLATA','LOKUM','ZEYTINYAGLAR','VALIZLER','TABAKLAR','PARFUM-ESANSLAR'];
$allowedCategories = array_map('mb_strtolower', $allowedCategories); // Hepsi küçük harfli olacak


$filteredData = array_filter($allProducts, function($urun) use ($allowedCategories) {
    $category = $urun['categories'][0]['title'] ?? '';
    return in_array(mb_strtolower($category), $allowedCategories);
});

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.collectapi.com/economy/exchange?int=10&to=TRY&base=USD",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => array(
    "authorization: apikey 7f1szhPPaYIfU8zHxPi670:4U08P8oeep1JjooBvZqGQt",
    "content-type: application/json"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
  $rate = 41; // fallback kur
} else {
  $dataP = json_decode($response, true);
  $rate = $dataP['result'][0]['rate'] ?? 41; // fallback kur
}
?>

<section id="price">
    <div class="container" id="urunler">
        <div class="row">
            <?php foreach ($filteredData as $urun): 
                $title = html_entity_decode($urun['title'], ENT_QUOTES, 'UTF-8');
                $desc = kisalt($urun['description']);
                $price = $urun['priceData']['discountedPrice'] ?? $urun['priceData']['price'];
                $currency = $urun['priceData']['currency'];
                $stock = $urun['stockQuantity'];
                $url = $urun['url'];
                $image = (!empty($urun['media']) && isset($urun['media'][0]['url']))? $urun['media'][0]['url']: 'default.jpg';
                $category = $urun['categories'][0]['title'] ?? 'Kategori Yok';

                    // Fiyat hesaplaması ürün bazında:
    if ($lang === 'en' || $lang === 'ar') {
        $priceUSD = $price / $rate;
        $displayPrice = number_format($priceUSD, 2, '.', '') . ' $';
    } else {
        $displayPrice = number_format($price, 2, ',', '.') . ' ₺';
    }

    $categories = [];
foreach ($filteredData as $urun) {
    $cat = $urun['categories'][0]['title'] ?? 'Kategori Yok';
    if (!in_array($cat, $categories)) {
        $categories[] = $cat;
    }
}
            ?>
            <!-- <div class="col-xs-12 col-sm-6 col-md-3 shadow-sm">
                <div class="panel panel-default transition curs">
                    <div class="panel-body text-center row">
                        <a href="<?= $url ?>" target="_blank">
                            <img src="<?= $image ?>" loading="lazy" alt="<?= htmlspecialchars($title) ?>" class="img-responsive center-block" style="max-height:400px; margin-bottom: 10px; border-radius:7px;">
                        </a>
                        <h3 style="font-size: 14px; background-color:#fff9e5ff; color: #9a7d1f; font-weight: 600; min-height: 60px; padding-top:20px;">
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
            <?php endforeach; ?>
        </div>
    </div> -->

<ul class="nav nav-tabs">
    <li class="active">
        <a data-toggle="tab" href="#all"><?= $translations[$lang]['categories']['tum-urunler'] ?? 'TÜM ÜRÜNLER' ?></a>
    </li>
    <?php foreach ($allowedCategories as $catKey): ?>
        <li>
            <a data-toggle="tab" href="#<?= $catKey ?>"><?= $translations[$lang]['categories'][$catKey] ?? strtoupper($catKey) ?></a>
        </li>
    <?php endforeach; ?>
</ul>




<!-- Sekme İçerikleri -->
<div class="tab-content">
    <div class="tab-pane fade in active" id="all">
        <div class="row">
            <?php foreach ($filteredData as $urun): 
                $rawCategory = $urun['categories'][0]['title'] ?? '';
                $categoryKey = mb_strtolower(str_replace(['Ç', 'Ğ', 'İ', 'Ö', 'Ş', 'Ü'], ['ç', 'ğ', 'i', 'ö', 'ş', 'ü'], $rawCategory));
                $categoryKey = str_replace([' '], '-', $categoryKey);
                if (!in_array($categoryKey, $allowedCategories)) continue;
                include 'urun_karti.php';
            endforeach; ?>
        </div>
    </div>

    <?php foreach ($allowedCategories as $catKey): ?>
        <div class="tab-pane fade" id="<?= $catKey ?>">
            <div class="row">
                <?php foreach ($filteredData as $urun): 
                    $rawCategory = $urun['categories'][0]['title'] ?? '';
                    $categoryKey = mb_strtolower(str_replace(['Ç', 'Ğ', 'İ', 'Ö', 'Ş', 'Ü'], ['ç', 'ğ', 'i', 'ö', 'ş', 'ü'], $rawCategory));
                    $categoryKey = str_replace([' '], '-', $categoryKey);
                    if ($categoryKey !== $catKey) continue;
                    include 'urun_karti.php';
                endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

    <!-- Kategoriye Özel Sekmeler -->
    <?php foreach ($filteredData as $urun): 
    $rawCategory = $urun['categories'][0]['title'] ?? 'tum-urunler';
    $categoryKey = mb_strtolower(str_replace(['Ç', 'Ğ', 'İ', 'Ö', 'Ş', 'Ü'], ['ç', 'ğ', 'i', 'ö', 'ş', 'ü'], $rawCategory));
    $categoryKey = str_replace([' '], '-', $categoryKey); // Örn: "Parfüm Esanslar" => "parfum-esanslar"

    if (!in_array($categoryKey, $allowedCategories)) continue; // Sadece izin verilenler

    $category = $translations[$lang]['categories'][$categoryKey] ?? $rawCategory;
    
?>
   
<?php endforeach; ?>

</div>
</section>

<script>
function stocklimit () {
    const stockElements = document.querySelectorAll('.stock-info');
    const stockLimitLabel = "<?= $stockLimitLabel ?>"

    stockElements.forEach(el => {
        const stock = parseInt(el.dataset.stock);
        if (stock <= 5) {
            el.style.color = "red";
            el.innerHTML +=  `(${stockLimitLabel}!)`;
        } else {
            el.style.color = "black";
        }
    });
}

stocklimit();
</script> 

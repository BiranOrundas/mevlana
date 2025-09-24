<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();
$totalPages = 10;
$limit = 50;
$allProducts = [];

for ($page = 1; $page <= $totalPages; $page++) {
    $url = "https://api.shopier.com/v1/products?limit=$limit&page=$page&sort=dateDesc";

    try {
        $response = $client->request('GET', $url, [
            'headers' => [
                'accept' => 'application/json',
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI2MTQ2N2Q4YjQ1OTk0ZTRmN2ZjMDI5NTM2ZDFhMDg5ZSIsImp0aSI6IjQxZmI0YjExNzRmNzI3ZjFlM2NjMjVlNzBiZjY3MTdkNTM1YzhmYWQxZWM2NjFjOWI5MWZkZGQzZjRkZDQ0NTMxZWM3NGNiN2MwOTcwZDcxMTUyMTNiMjljNjk3MDU1YzE5MjYwM2ZhOTdiZTlkNWI2ZTAzZjg4NjNmNjhlZDQyN2JkZmM5MzMzNDYwYTgxYjY2YzZhYmNmMjBkOWMzN2QiLCJpYXQiOjE3NTc3NTUyNjIsIm5iZiI6MTc1Nzc1NTI2MiwiZXhwIjoxOTE1NTQwMDIyLCJzdWIiOiI4ODM0NTAiLCJzY29wZXMiOlsib3JkZXJzOnJlYWQiLCJvcmRlcnM6d3JpdGUiLCJwcm9kdWN0czpyZWFkIiwicHJvZHVjdHM6d3JpdGUiLCJzaGlwcGluZ3M6cmVhZCIsInNoaXBwaW5nczp3cml0ZSIsImRpc2NvdW50czpyZWFkIiwiZGlzY291bnRzOndyaXRlIiwicGF5b3V0czpyZWFkIiwicmVmdW5kczpyZWFkIiwicmVmdW5kczp3cml0ZSIsInNob3A6cmVhZCIsInNob3A6d3JpdGUiXX0.IumIWKDP0CkMTgMAAMoBr8fgKaNNLja-bal24FI-B_qkq8G-UvQRyAXoQg6kHjTrhBfzoygnZ0QJmTlzsgt9uA3JGGBDfi_d0tRPpF4hoI4M7hn-C_w6OAB7ZUxqVGWwkkFPjt_Bo7f8T7WGv6x3mK5MPbmQl_AXGyYegOMEaaXyOx_XfesLqFXEIeZ6mNdjgEoKnbsMuTCKueY6A57mSJtohsan0vuJdz-1Fi8ncJT01TFYyRhsDDsr4jwVjHoTkRGilh2EtGnKdNsHIbegN9JqMgHvPjFCXuXzJBx6DABX2zfF5u_nqESSeis7cSssoUlBWxTog8yhZgdBH5tBrA', // <-- Burayı güvenli şekilde değiştir
            ],
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "JSON Decode Hatası: " . json_last_error_msg();
            break;
        }

        if (!empty($data)) {
            $allProducts = array_merge($allProducts, $data);
            if (count($data) < $limit) {
                break; // Son sayfaya ulaşıldı
            }
        } else {
            echo "Veri alınamadı.";
            break;
        }
    } catch (\Exception $e) {
        echo "Hata: " . $e->getMessage();
        break;
    }
}

// Kısa açıklama için yardımcı fonksiyon
function kisalt($text, $limit = 100) {
    $text = strip_tags($text);
    return mb_strimwidth($text, 0, $limit, "...");
}

// HTML Görüntüleme

// XML OLUŞTUR
$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rss/>');
$xml->addAttribute('version', '2.0');
$xml->addAttribute('xmlns:g', 'http://base.google.com/ns/1.0');

$channel = $xml->addChild('channel');
$channel->addChild('title', 'Mağaza Adı');
$channel->addChild('link', 'https://www.shopier.com/YOUR_SHOP_URL');
$channel->addChild('description', 'Shopier mağazanızın ürünleri');

foreach ($allProducts as $urun) {
    $item = $channel->addChild('item');
    $item->addChild('g:id', $urun['id'], 'http://base.google.com/ns/1.0');
    $item->addChild('g:title', htmlspecialchars($urun['title']), 'http://base.google.com/ns/1.0');
    $item->addChild('g:description', htmlspecialchars(strip_tags($urun['description'])), 'http://base.google.com/ns/1.0');
    $item->addChild('g:link', $urun['url'], 'http://base.google.com/ns/1.0');

    if (!empty($urun['media'][0]['url'])) {
        $item->addChild('g:image_link', $urun['media'][0]['url'], 'http://base.google.com/ns/1.0');
    }

    $fiyat = $urun['priceData']['discountedPrice'] ?? $urun['priceData']['price'];
    $paraBirim = $urun['priceData']['currency'] ?? 'TRY';
    $item->addChild('g:price', number_format($fiyat, 2, '.', '') . ' ' . $paraBirim, 'http://base.google.com/ns/1.0');

    $availability = $urun['stockQuantity'] > 0 ? 'in stock' : 'out of stock';
    $item->addChild('g:availability', $availability, 'http://base.google.com/ns/1.0');

    $item->addChild('g:brand', 'MarkaAdı', 'http://base.google.com/ns/1.0');
    if (!empty($urun['categories'][0]['title'])) {
        $item->addChild('g:product_type', $urun['categories'][0]['title'], 'http://base.google.com/ns/1.0');
    }

    $item->addChild('g:condition', 'new', 'http://base.google.com/ns/1.0');
}

$xmlFile = 'google_feed.xml';
$xml->asXML($xmlFile);
echo "<p>Google XML Feed oluşturuldu: <a href='$xmlFile' target='_blank'>$xmlFile</a></p>";
?>

<?php
require_once('vendor/autoload.php');
use GuzzleHttp\Client;

$client = new Client();

$totalPages = 10;
$limit = 50;
$allProducts = [];

for ($page = 1; $page <= $totalPages; $page++) {
    $url = "https://api.shopier.com/v1/products?limit=$limit&page=$page&sort=dateDesc";

    try {
        $response = $client->request('GET', $url, [
            'headers' => [
                'accept' => 'application/json',
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI2MTQ2N2Q4YjQ1OTk0ZTRmN2ZjMDI5NTM2ZDFhMDg5ZSIsImp0aSI6IjQxZmI0YjExNzRmNzI3ZjFlM2NjMjVlNzBiZjY3MTdkNTM1YzhmYWQxZWM2NjFjOWI5MWZkZGQzZjRkZDQ0NTMxZWM3NGNiN2MwOTcwZDcxMTUyMTNiMjljNjk3MDU1YzE5MjYwM2ZhOTdiZTlkNWI2ZTAzZjg4NjNmNjhlZDQyN2JkZmM5MzMzNDYwYTgxYjY2YzZhYmNmMjBkOWMzN2QiLCJpYXQiOjE3NTc3NTUyNjIsIm5iZiI6MTc1Nzc1NTI2MiwiZXhwIjoxOTE1NTQwMDIyLCJzdWIiOiI4ODM0NTAiLCJzY29wZXMiOlsib3JkZXJzOnJlYWQiLCJvcmRlcnM6d3JpdGUiLCJwcm9kdWN0czpyZWFkIiwicHJvZHVjdHM6d3JpdGUiLCJzaGlwcGluZ3M6cmVhZCIsInNoaXBwaW5nczp3cml0ZSIsImRpc2NvdW50czpyZWFkIiwiZGlzY291bnRzOndyaXRlIiwicGF5b3V0czpyZWFkIiwicmVmdW5kczpyZWFkIiwicmVmdW5kczp3cml0ZSIsInNob3A6cmVhZCIsInNob3A6d3JpdGUiXX0.IumIWKDP0CkMTgMAAMoBr8fgKaNNLja-bal24FI-B_qkq8G-UvQRyAXoQg6kHjTrhBfzoygnZ0QJmTlzsgt9uA3JGGBDfi_d0tRPpF4hoI4M7hn-C_w6OAB7ZUxqVGWwkkFPjt_Bo7f8T7WGv6x3mK5MPbmQl_AXGyYegOMEaaXyOx_XfesLqFXEIeZ6mNdjgEoKnbsMuTCKueY6A57mSJtohsan0vuJdz-1Fi8ncJT01TFYyRhsDDsr4jwVjHoTkRGilh2EtGnKdNsHIbegN9JqMgHvPjFCXuXzJBx6DABX2zfF5u_nqESSeis7cSssoUlBWxTog8yhZgdBH5tBrA',
            ],
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "JSON Decode Hatası: " . json_last_error_msg() . PHP_EOL;
            break;
        }

        if (!empty($data)) {
            $allProducts = array_merge($allProducts, $data);
            if (count($data) < $limit) {
                break;
            }
        } else {
            echo "API'den veri alınamadı veya veri boş." . PHP_EOL;
            break;
        }
    } catch (\Exception $e) {
        echo "API isteği sırasında hata: " . $e->getMessage() . PHP_EOL;
        break;
    }
}

$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rss version="2.0" xmlns:g="http://base.google.com/ns/1.0"></rss>');
$channel = $xml->addChild('channel');
$channel->addChild('title', 'Mevlana Bazaar');
$channel->addChild('link', 'https://www.shopier.com/mevlanapolatbazaar');
$channel->addChild('description', 'Ürün Feed');

foreach ($allProducts as $product) {
    $item = $channel->addChild('item');
    $item->addChild('g:id', $product['id'], 'http://base.google.com/ns/1.0');
    $item->addChild('g:title', trim($product['title']), 'http://base.google.com/ns/1.0');

    $descRaw = html_entity_decode($product['description']);
    $description = preg_replace('/<!--.*?-->/s', '', $descRaw);
    $description = trim($description);

    $descNode = $item->addChild('g:description', '', 'http://base.google.com/ns/1.0');
    $descDom = dom_import_simplexml($descNode);
    $descOwner = $descDom->ownerDocument;
    $descDom->appendChild($descOwner->createCDATASection($description));
    
    $item->addChild('g:link', $product['url'], 'http://base.google.com/ns/1.0');

    if (!empty($product['media'][0]['url'])) {
        $item->addChild('g:image_link', $product['media'][0]['url'], 'http://base.google.com/ns/1.0');
    } else {
        $item->addChild('g:image_link', '', 'http://base.google.com/ns/1.0');
    }

    $price = $product['priceData']['discountedPrice'] ?? $product['priceData']['price'] ?? 0;
    $currency = $product['priceData']['currency'] ?? 'TRY';
    $priceFormatted = number_format((float)$price, 2, '.', '');
    $item->addChild('g:price', $priceFormatted . ' ' . $currency, 'http://base.google.com/ns/1.0');

    $availability = ($product['stockStatus'] ?? 'inStock') === 'inStock' ? 'in stock' : 'out of stock';
    $item->addChild('g:availability', $availability, 'http://base.google.com/ns/1.0');

    $brand = $product['brand'] ?? ($product['categories'][0]['title'] ?? 'Marka');
    $item->addChild('g:brand', $brand, 'http://base.google.com/ns/1.0');

    $item->addChild('g:condition', 'new', 'http://base.google.com/ns/1.0');
}

$dom = new DOMDocument('1.0', 'UTF-8');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($xml->asXML());

echo $dom->saveXML();

$file = __DIR__ . '/products.xml';
if ($dom->save($file)) {
    echo "Google Merchant uyumlu XML dosyası oluşturuldu: $file" . PHP_EOL;
} else {
    echo "XML dosyası oluşturulurken hata oluştu." . PHP_EOL;
}

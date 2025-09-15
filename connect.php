<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('GET', 'https://api.shopier.com/v1/products?limit=50&page=1&sort=dateDesc', [
  'headers' => [
    'accept' => 'application/json',
    'authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI2MTQ2N2Q4YjQ1OTk0ZTRmN2ZjMDI5NTM2ZDFhMDg5ZSIsImp0aSI6IjQxZmI0YjExNzRmNzI3ZjFlM2NjMjVlNzBiZjY3MTdkNTM1YzhmYWQxZWM2NjFjOWI5MWZkZGQzZjRkZDQ0NTMxZWM3NGNiN2MwOTcwZDcxMTUyMTNiMjljNjk3MDU1YzE5MjYwM2ZhOTdiZTlkNWI2ZTAzZjg4NjNmNjhlZDQyN2JkZmM5MzMzNDYwYTgxYjY2YzZhYmNmMjBkOWMzN2QiLCJpYXQiOjE3NTc3NTUyNjIsIm5iZiI6MTc1Nzc1NTI2MiwiZXhwIjoxOTE1NTQwMDIyLCJzdWIiOiI4ODM0NTAiLCJzY29wZXMiOlsib3JkZXJzOnJlYWQiLCJvcmRlcnM6d3JpdGUiLCJwcm9kdWN0czpyZWFkIiwicHJvZHVjdHM6d3JpdGUiLCJzaGlwcGluZ3M6cmVhZCIsInNoaXBwaW5nczp3cml0ZSIsImRpc2NvdW50czpyZWFkIiwiZGlzY291bnRzOndyaXRlIiwicGF5b3V0czpyZWFkIiwicmVmdW5kczpyZWFkIiwicmVmdW5kczp3cml0ZSIsInNob3A6cmVhZCIsInNob3A6d3JpdGUiXX0.IumIWKDP0CkMTgMAAMoBr8fgKaNNLja-bal24FI-B_qkq8G-UvQRyAXoQg6kHjTrhBfzoygnZ0QJmTlzsgt9uA3JGGBDfi_d0tRPpF4hoI4M7hn-C_w6OAB7ZUxqVGWwkkFPjt_Bo7f8T7WGv6x3mK5MPbmQl_AXGyYegOMEaaXyOx_XfesLqFXEIeZ6mNdjgEoKnbsMuTCKueY6A57mSJtohsan0vuJdz-1Fi8ncJT01TFYyRhsDDsr4jwVjHoTkRGilh2EtGnKdNsHIbegN9JqMgHvPjFCXuXzJBx6DABX2zfF5u_nqESSeis7cSssoUlBWxTog8yhZgdBH5tBrA',
  ],
]);


 $body = $response->getBody();
$data = json_decode($body, true); // JSON → PHP dizi
// echo ('<pre>');
// print_r($data);
// echo ('<pre>');

// $data = json_decode($response->getBody(), true); // JSON'dan diziye dönüştürdüğünü varsayıyoruz

// echo '<div class="urunler">';
// foreach ($data as $urun) {
//     $id = $urun['id'];
//     $title = $urun['title'];
//     $description = $urun['description'];
//     $price = $urun['priceData']['discountedPrice'] ?? $urun['priceData']['price'];
//     $currency = $urun['priceData']['currency'];
//     $stock = $urun['stockQuantity'];
//     $url = $urun['url'];

//     // İlk görsel (varsa)
//     $image = isset($urun['media'][0]['url']) ? $urun['media'][0]['url'] : 'default.jpg';

//     // Kategori
//     $category = isset($urun['categories'][0]['title']) ? $urun['categories'][0]['title'] : 'Kategori Yok';

//     echo "<div class='urun'>
//         <h2>{$title}</h2>
//         <a href='{$url}' target='_blank'>
//             <img src='{$image}' alt='{$title}' width='200'>
//         </a>
//         <p><strong>Açıklama:</strong> {$description}</p>
//         <p><strong>Fiyat:</strong> {$price} {$currency}</p>
//         <p><strong>Stok:</strong> {$stock}</p>
//         <p><strong>Kategori:</strong> {$category}</p>
//     </div><hr>";
// }
// echo '</div>';
?>


 
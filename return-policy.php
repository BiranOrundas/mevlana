<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Return & Exchange Policy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.7;
            background-color: #f9f9f9;
            padding: 40px 20px;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        h1, h2 {
            color: #9a7d1f;
        }

        ul {
            padding-left: 20px;
        }

        .notice {
            background-color: #fff7e6;
            border-left: 4px solid #f1c40f;
            padding: 10px 15px;
            margin: 20px 0;
        }

        .lang-toggle {
            margin-bottom: 20px;
        }

        .lang-toggle button {
            padding: 8px 15px;
            margin-right: 10px;
            background-color: #9a7d1f;
            color: white;
            border: none;
            cursor: pointer;
        }

        .lang-toggle button:hover {
            opacity: 0.9;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Return & Exchange Policy</h1>

    <div class="lang-toggle">
        <button onclick="toggleLang('en')">English</button>
        <button onclick="toggleLang('tr')">Türkçe</button>
    </div>

    <div id="policy-en">
        <p>Thank you for shopping with us!</p>

        <div class="notice"><strong>All sales are final.</strong> We do <strong>not accept any returns or exchanges</strong>.</div>

        <p>This includes but is not limited to:</p>
        <ul>
            <li>Change of mind</li>
            <li>Incorrect product or size selection</li>
            <li>Allergic reactions</li>
            <li>Courier delays or shipping issues beyond our control</li>
        </ul>

        <p>Please make sure to carefully review all product details before completing your purchase.</p>

        <p><strong>If a product arrives damaged or defective</strong>, contact us within <strong>48 hours</strong> of delivery with photo proof. We’ll do our best to assist.</p>

        <p>By placing an order, you agree to this return & exchange policy.</p>
    </div>

    <div id="policy-tr" class="hidden">
        <h2>İade ve Değişim Politikası</h2>

        <p>Bizden alışveriş yaptığınız için teşekkür ederiz!</p>

        <div class="notice"><strong>Tüm satışlar kesindir.</strong> <strong>İade veya değişim kabul edilmemektedir.</strong></div>

        <p>Buna aşağıdaki durumlar dahildir (ancak bunlarla sınırlı değildir):</p>
        <ul>
            <li>Karar değişikliği</li>
            <li>Yanlış ürün veya beden seçimi</li>
            <li>Alerjik reaksiyonlar</li>
            <li>Bizden kaynaklanmayan kargo gecikmeleri veya sorunları</li>
        </ul>

        <p>Lütfen satın alma işlemini tamamlamadan önce ürün detaylarını dikkatlice inceleyiniz.</p>

        <p><strong>Eğer ürün hasarlı ya da kusurlu geldiyse</strong>, teslimattan sonraki <strong>48 saat</strong> içinde bizimle iletişime geçin ve fotoğraf gönderin. Size yardımcı olmaya çalışacağız.</p>

        <p>Sitemizden sipariş vererek bu iade ve değişim politikasını kabul etmiş olursunuz.</p>
    </div>
</div>

<script>
function toggleLang(lang) {
    document.getElementById('policy-en').classList.add('hidden');
    document.getElementById('policy-tr').classList.add('hidden');

    if (lang === 'en') {
        document.getElementById('policy-en').classList.remove('hidden');
    } else if (lang === 'tr') {
        document.getElementById('policy-tr').classList.remove('hidden');
    }
}
</script>
</body>
</html>

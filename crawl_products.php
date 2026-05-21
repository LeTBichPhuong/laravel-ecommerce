<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpClient\HttpClient;

$http = HttpClient::create();

echo "=== BẮT ĐẦU TẠO DỮ LIỆU SẢN PHẨM ===\n\n";

// Logo brand
$brandLogos = [
    'Nike'        => 'https://upload.wikimedia.org/wikipedia/commons/a/a6/Logo_NIKE.svg',
    'Adidas'      => 'https://upload.wikimedia.org/wikipedia/commons/2/20/Adidas_Logo.svg',
    'Puma'        => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/88/Puma_logo.svg/800px-Puma_logo.svg.png',
    'New Balance' => 'https://upload.wikimedia.org/wikipedia/commons/e/ea/New_Balance_logo.svg',
    'Converse'    => 'https://upload.wikimedia.org/wikipedia/commons/3/30/Converse_logo.svg',
    'Vans'        => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/60/Vans-logo.svg/800px-Vans-logo.svg.png',
    'Reebok'      => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0c/Reebok_logo.svg/800px-Reebok_logo.svg.png',
    'Asics'       => 'https://upload.wikimedia.org/wikipedia/commons/b/b1/Asics_Logo.svg',
    'Jordan'      => 'https://upload.wikimedia.org/wikipedia/en/3/37/Jumpman_logo.svg',
    'Gucci'       => 'https://upload.wikimedia.org/wikipedia/commons/7/79/1960s_Gucci_Logo.svg',
    'Balenciaga'  => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5f/Balenciaga_logo.svg/800px-Balenciaga_logo.svg.png',
];

// Ảnh giày (Unsplash)
$shoeImages = [
    "https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=300",
    "https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?w=300",
    "https://images.unsplash.com/photo-1528701800489-20be3cbbf4a2?w=300",
    "https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=300",
    "https://images.unsplash.com/photo-1584735175315-9d5df23be620?w=300",
    "https://images.unsplash.com/photo-1543508282-6319a3e2621f?w=300",
    "https://images.unsplash.com/photo-1560769629-975ec94e6a86?w=300",
    "https://images.unsplash.com/photo-1608231387042-66d1773070a5?w=300",
    "https://images.unsplash.com/photo-1575537302964-96cd47c06b1b?w=300",
    "https://images.unsplash.com/photo-1519744346363-d8d8f1f6a0df?w=300",
    "https://images.unsplash.com/photo-1582582429416-2d5f0b4f5c4e?w=300",
    "https://images.unsplash.com/photo-1606813902914-52c3b8f3a0c1?w=300",
    "https://images.unsplash.com/photo-1600180758890-6b94519a8ba6?w=300",
    "https://images.unsplash.com/photo-1588361861040-ac9b1018f6d5?w=300",
    "https://images.unsplash.com/photo-1614252235316-8c857d38b5f4?w=300",
    "https://images.unsplash.com/photo-1600269452121-4f2416e55c28?w=300",
    "https://images.unsplash.com/photo-1593032465175-481ac7f401a0?w=300",
    "https://images.unsplash.com/photo-1584735174959-2c2c0b74c1bb?w=300",
    "https://images.unsplash.com/photo-1580906855288-57d72f6f7b5c?w=300",
    "https://images.unsplash.com/photo-1597045566677-8cf032ed6634?w=300",
];

// Tên giày
$shoeNames = [
    'Running Shoes', 'Sneakers', 'Sport Shoes',
    'Training Shoes', 'Casual Shoes', 'Air Sneakers',
];

// Download logo về local
$logoDir = __DIR__ . '/storage/app/public/logos/';
if (!is_dir($logoDir)) {
    mkdir($logoDir, 0755, true);
}

echo "--- Đang tải logo thương hiệu ---\n";

$downloadedLogos = [];

foreach ($brandLogos as $brand => $url) {
    // Lấy đúng extension từ URL (svg hoặc png)
    $ext      = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
    $ext      = in_array(strtolower($ext), ['svg', 'png', 'jpg', 'jpeg', 'webp']) ? strtolower($ext) : 'svg';
    $filename = strtolower(str_replace(' ', '_', $brand)) . '.' . $ext;
    $savePath  = $logoDir . $filename;
    $publicPath = 'storage/logos/' . $filename;

    // Nếu đã tải trước đó thì bỏ qua
    if (file_exists($savePath)) {
        $downloadedLogos[$brand] = $publicPath;
        echo "⏭  $brand — đã có sẵn, bỏ qua\n";
        continue;
    }

    try {
        $response = $http->request('GET', $url, [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (compatible; SeedBot/1.0; +https://example.com)',
            ],
            'timeout'       => 15,
            'max_redirects' => 5,
        ]);

        $statusCode = $response->getStatusCode();

        if ($statusCode === 200) {
            file_put_contents($savePath, $response->getContent());
            $downloadedLogos[$brand] = $publicPath;
            echo "$brand — OK\n";
        } else {
            $downloadedLogos[$brand] = $url;
            echo "$brand — HTTP $statusCode, dùng URL gốc\n";
        }
    } catch (\Exception $e) {
        $downloadedLogos[$brand] = $url;
        echo "$brand — Lỗi: " . $e->getMessage() . "\n";
    }
}

echo "\n";

// Tạo dữ liệu sản phẩm
echo "--- Đang tạo dữ liệu sản phẩm ---\n";

$results = [];

foreach ($brandLogos as $brand => $logo) {
    $numProducts = rand(10, 15);

    for ($i = 0; $i < $numProducts; $i++) {
        $allSizes = ['38', '39', '40', '41', '42', '43', '44'];
        shuffle($allSizes);
        $sizes = array_slice($allSizes, 0, rand(3, 5));

        $name  = $brand . ' ' . $shoeNames[array_rand($shoeNames)] . ' ' . rand(100, 999);
        $image = $shoeImages[array_rand($shoeImages)];

        $genderRoll = rand(0, 2);
        $gender = $genderRoll === 0 ? 'Men' : ($genderRoll === 1 ? 'Women' : 'Unisex');

        $results[] = [
            'brand'       => $brand,
            'brand_logo'  => $downloadedLogos[$brand] ?? $logo,
            'name'        => $name,
            'price'       => rand(800000, 5000000),
            'image'       => $image,
            'gender'      => $gender,
            'description' => 'Giày ' . $brand . ' chính hãng, thiết kế hiện đại.',
            'sizes'       => $sizes,
        ];
    }

    echo "✅  $brand — {$numProducts} sản phẩm\n";
}

// Lưu file JSON
$storageDir = __DIR__ . '/storage/app/';
if (!is_dir($storageDir)) {
    mkdir($storageDir, 0755, true);
}

$filePath = $storageDir . 'products.json';

file_put_contents(
    $filePath,
    json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

echo "\n=== HOÀN TẤT ===\n";
echo "Tổng sản phẩm : " . count($results) . "\n";
echo "File JSON      : $filePath\n";
echo "Thư mục logo   : $logoDir\n";

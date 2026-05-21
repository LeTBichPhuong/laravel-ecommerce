<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\ProductController;

$controller = new ProductController();
echo "Đang bắt đầu import dữ liệu sản phẩm...\n";
$response = $controller->import();
echo "Status code: " . $response->status() . "\n";
echo "Nội dung phản hồi: " . json_encode($response->getData(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

<?php
 
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Brand;
 
// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
 
echo "Bắt đầu import sản phẩm...\n";
 
$path = storage_path('app/products.json');
if (!file_exists($path)) {
    die("Lỗi: Không tìm thấy file products.json tại $path\n");
}
 
$json = file_get_contents($path);
$data = json_decode($json, true);
 
if (!$data) {
    die("Lỗi: File JSON rỗng hoặc lỗi định dạng\n");
}
 
try {
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    DB::table('product_sizes')->truncate();
    DB::table('products')->truncate();
    DB::table('brands')->truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
 
    $count = 0;
    foreach ($data as $item) {
        // Xử lý Brand
        $brand = Brand::firstOrCreate(
            ['name' => $item['brand']]
        );
 
        // Tạo sản phẩm
        $product = Product::create([
            'brand_id'    => $brand->id,
            'name'        => $item['name'],
            'price'       => $item['price'],
            'description' => $item['description'] ?? '',
            'image'       => $item['image'] ?? null,
            'gender'      => $item['gender'] ?? 'Unisex',
        ]);
 
        // Thêm kích thước (sizes)
        if (isset($item['sizes']) && is_array($item['sizes'])) {
            foreach ($item['sizes'] as $size) {
                DB::table('product_sizes')->insert([
                    'product_id' => $product->id,
                    'size'       => $size,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        $count++;
        if ($count % 50 === 0) echo "Đã import $count sản phẩm...\n";
    }
 
    echo "Thành công! Đã import $count sản phẩm.\n";
} catch (\Exception $e) {
    echo "Lỗi import: " . $e->getMessage() . "\n";
}

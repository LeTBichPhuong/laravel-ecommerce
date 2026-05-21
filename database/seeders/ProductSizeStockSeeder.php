<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSizeStockSeeder extends Seeder
{
    /**
     * Gán số lượng tồn kho ảo (ngẫu nhiên có trọng số) cho từng size giày.
     *
     * Phân phối tồn kho:
     *  - Size trung bình (40-42) → nhiều hàng hơn (20-60 đôi)
     *  - Size nhỏ/lớn (36-38, 44-46) → ít hơn (5-25 đôi)
     *  - Một số size có thể hết hàng (stock = 0) để tạo cảm giác thực tế
     */
    public function run(): void
    {
        // Bản đồ trọng số stock theo size (size => [min, max])
        $stockRange = [
            '35'  => [3,  15],
            '36'  => [5,  20],
            '37'  => [8,  25],
            '38'  => [10, 35],
            '39'  => [15, 50],
            '40'  => [20, 60],
            '41'  => [20, 60],
            '42'  => [15, 55],
            '43'  => [10, 40],
            '44'  => [5,  25],
            '45'  => [3,  18],
            '46'  => [2,  12],
            '47'  => [1,  8],
        ];

        // Lấy tất cả bản ghi product_sizes
        $sizes = DB::table('product_sizes')->get();

        if ($sizes->isEmpty()) {
            $this->command->warn('  Bảng product_sizes trống! Hãy chạy import sản phẩm trước.');
            return;
        }

        $updated = 0;
        foreach ($sizes as $row) {
            $size = trim((string) $row->size);
            
            // Lấy range theo size, fallback nếu size không có trong bản đồ
            [$min, $max] = $stockRange[$size] ?? [5, 30];

            // ~15% xác suất hết hàng để tạo cảm giác thực
            $stock = (rand(1, 100) <= 15) ? 0 : rand($min, $max);

            DB::table('product_sizes')
                ->where('id', $row->id)
                ->update([
                    'stock'      => $stock,
                    'updated_at' => now(),
                ]);
            
            $updated++;
        }

        $this->command->info("  ✓ Đã cập nhật stock cho {$updated} size records.");
    }
}

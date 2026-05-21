<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],  
            [
                'email' => 'admin90@gmail.com',
                'password' => Hash::make('123123'),  
                'role' => 'admin', 
                'email_verified_at' => now(),
            ]
        );

        $path = storage_path('app/products.json');
        if (file_exists($path)) {
            $json = file_get_contents($path);
            $data = json_decode($json, true);
            foreach ($data as $item) {
                $brandName = $item['brand'] ?? 'Unknown';
                if (empty($brandName)) $brandName = 'Unknown';
                
                $brand = \App\Models\Brand::firstOrCreate(
                    ['name' => $brandName],
                    ['logo' => $item['brand_logo'] ?? null]
                );

                $product = \App\Models\Product::create([
                    'brand_id' => $brand->id,
                    'name' => $item['name'] ?? 'No Name',
                    'price' => $item['price'] ?? 0,
                    'image' => $item['image'] ?? null,
                    'gender' => $item['gender'] ?? 'Unisex',
                    'description' => $item['description'] ?? '',
                ]);

                if (!empty($item['sizes']) && is_array($item['sizes'])) {
                    foreach ($item['sizes'] as $size) {
                        \Illuminate\Support\Facades\DB::table('product_sizes')->insert([
                            'product_id' => $product->id,
                            'size' => $size,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand; 
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Helpers\PriceHelper;

class ProductController extends Controller
{
    /**
     * Helper function để tải tất cả các thương hiệu
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function loadAllBrands()
    {
        if (class_exists(Brand::class) && method_exists(Brand::class, 'has')) {
            return Brand::has('products')->get();
        }
        return collect([]); 
    }   

    /**
     * Hiển thị trang chủ 
     * @return \Illuminate\View\View
     */
    public function home()
    {
        // Debug: Kiểm tra view có tồn tại không
        $viewPath = resource_path('views/Home.blade.php');
        
        if (!file_exists($viewPath)) {
            return response()->json([
                'error' => 'View not found',
                'path' => $viewPath,
                'views_dir' => scandir(resource_path('views'))
            ], 404);
        }
        
        return view('Home');
    }

    
    // Hiển thị danh sách sản phẩm 
    public function index(Request $request)
    {
        $query = Product::with('brand');

        // Lọc theo thương hiệu
        if ($request->brand) {
            $query->where('brand_id', $request->brand);
        }

        // Lọc theo giới tính (nếu bạn muốn giữ)
        if ($request->gender && $request->gender !== 'all') {
            $map = [
                'Men' => 'Men',
                'Unisex' => 'Unisex',
                'Women' => 'Women'
            ];

            if (isset($map[$request->gender])) {
                $query->where('gender', $map[$request->gender]);
            }
        }

        // Lấy sản phẩm có phân trang (16 items = 4 hàng x 4 sản phẩm)
        $products = $query->paginate(16)->appends($request->query()); 

        // Lấy list brand cho sidebar
        $allBrands = Brand::has('products')->get();

        return view('Layouts.MainProduct', [
            'products' => $products,
            'allBrands' => $allBrands,
            'title' => 'SẢN PHẨM',
            'description' => 'Bộ sưu tập sản phẩm cao cấp Luma Shoes',
            'active_gender' => $request->gender ?? 'all',
            'active_brand' => $request->brand ?? null,
        ]);
    }

    // Lọc sản phẩm thêm thương hiệu
    public function filterByBrand($brandId)
    {
        // Lấy thương hiệu
        $brand = Brand::findOrFail($brandId);

        // Lọc sản phẩm theo brand và phân trang
        $products = Product::query()->where('brand_id', $brandId)->paginate(16);

        // Lấy tất cả brand để hiển thị menu bên trái
        $allBrands = Brand::has('products')->get();

        return view('Layouts.MainProduct', [
            'products' => $products,
            'allBrands' => $allBrands,
            'title' => 'Thương hiệu: ' . $brand->name,
            'description' => 'Các sản phẩm đến từ thương hiệu ' . $brand->name,
            'active_brand' => $brandId,
        ]);
    }

    // Import dữ liệu từ file JSON 
    public function import()
    {
        $path = storage_path('app/products.json');
        if (!file_exists($path)) {
            return response()->json(['error' => 'Không tìm thấy file products.json'], 404);
        }

        $json = file_get_contents($path);
        $data = json_decode($json, true);

        if (!$data) {
            return response()->json(['error' => 'File JSON rỗng hoặc lỗi định dạng'], 400);
        }

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('product_sizes')->truncate();
            DB::table('products')->truncate();
            DB::table('brands')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

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
            }

            return response()->json([
                'message' => 'Cập nhật dữ liệu thành công!',
                'total_products' => count($data)
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi import: ' . $e->getMessage()], 500);
        }
    }

    // API trả về JSON file crawl gốc
        // public function getJson()
        // {
        //     $path = storage_path('app/products.json');
        //     if (!file_exists($path)) {
        //         return response()->json(['error' => 'File JSON không tồn tại'], 404);
        //     }

        //     $json = file_get_contents($path);
        //     $data = json_decode($json, true);

        //     if (json_last_error() !== JSON_ERROR_NONE) {
        //         return response()->json(['error' => 'Lỗi parse JSON: ' . json_last_error_msg()], 500);
        //     }

        //     return response()->json($data);
        // }
        public function getJson()
        {
            try {
                $products = Product::with('brand')->get();

                if ($products->isEmpty()) {
                    return response()->json(['error' => 'Không có sản phẩm nào trong cơ sở dữ liệu.'], 404);
                }

                // Gom nhóm theo brand
                $grouped = $products->groupBy('brand.name')->map(function ($items, $brandName) {
                    return [
                        'name' => $brandName,
                        'products' => $items->map(function ($p) {
                            return [
                                'id'    => $p->id,
                                'name'  => $p->name,
                                'price' => $p->price,
                                'image' => $p->image,
                                'gender' => $p->gender,
                                'brand' => $p->brand->name,
                            ];
                        })->toArray()
                    ];
                })->values();

                return response()->json($grouped);

            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

    // Method show() cho chi tiết sản phẩm
    public function show($id) {
        $product = Product::with(['brand', 'sizes'])->findOrFail($id);

        // Lấy sản phẩm gợi ý
        $featuredProducts = Product::with('brand')
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(12)
            ->get();

        return view('Layouts.MainShow', [
            'product' => $product,
            'featuredProducts' => $featuredProducts
        ]);
    }

    // Method quickView cho modal xem trước
    public function quickView($id) {
        try {
            $product = Product::with(['brand', 'sizes'])->findOrFail($id);
            return response()->json([
                'success' => true,
                'product' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }
    }

    // Giới tính
    public function showByGender($gender) 
    {
        // Map route gender -> database gender
        $map = [
            'Men' => 'Men',
            'Unisex' => 'Unisex',
            'Women' => 'Women'
        ];

        // Nếu không tồn tại trong map thì trả về 404
        if (!isset($map[$gender])) {
            abort(404);
        }

        $dbGender = $map[$gender];

        $products = Product::with('brand')
            ->where('gender', $dbGender)
            ->paginate(16);

        $allBrands = Brand::has('products')->get();

        $gender_map = [
            'Men' => 'Giày nam',
            'Women' => 'Giày nữ',
            'Unisex' => 'Giày unisex'
        ];

        return view('Layouts.MainProduct', [
            'products' => $products,
            'allBrands' => $allBrands, 
            'title' => $gender_map[$gender],
            'description' => "Bộ sưu tập " . strtolower($gender_map[$gender]) . " sang trọng, độc đáo từ Luma Shoes.",
            'active_gender' => $gender,
        ]);
    }

    // Tìm kiếm sản phẩm 
    public function search(Request $request)
    {
        $keyword = $request->get('keyword', '');

        if (empty($keyword)) {
            return response()->json(['products' => [], 'brands' => []]);
        }

        // Lấy dữ liệu trực tiếp từ MySQL
        $products = Product::with('brand')
            ->where('name', 'like', "%{$keyword}%")
            ->orWhere('description', 'like', "%{$keyword}%")
            ->take(10)
            ->get(['id', 'brand_id', 'name', 'price', 'image']);

        $brand = Brand::query()->where('name', 'like', "%{$keyword}%")
            ->take(5)
            ->get(['id', 'name', 'logo']);

        return response()->json([
            'products' => $products,
            'brand' => $brand,
        ]);
    }

    // thêm giỏ hàng phải đăng nhập
    public function add($id)
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Vui lòng đăng nhập!');
            }
            
            $userId = Auth::id();
            $product = Product::findOrFail($id);
            
            $cartItem = Cart::query()->where('user_id', $userId)
                            ->where('product_id', $id)
                            ->first();
            
            if ($cartItem) {
                $cartItem->quantity += 1;
                $cartItem->save();
            } else {
                Cart::create([
                    'user_id' => $userId,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_image' => $product->image,
                    'price' => $product->price,
                    'quantity' => 1,
                ]);
            }
            
            return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng!');
            
        } catch (\Exception $e) {
            Log::error('Add to cart error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra!');
        }
    }
}
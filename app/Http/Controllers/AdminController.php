<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel; 
use App\Exports\OrdersExport;


class AdminController extends Controller
{
    // Kiểm tra quyền admin
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    // Dashboard
    public function dashboard()
    {
        $soldOrdersCount = Order::whereIn('status', ['confirmed', 'shipped', 'delivered'])->count();
        $revenue = Order::whereIn('status', ['confirmed', 'shipped', 'delivered'])->sum('total');
        $customerCount = User::whereHas('orders')->count();
        $totalOrders = Order::count();
        $monthlyRevenue = Order::selectRaw('EXTRACT(MONTH FROM created_at) AS month, SUM(total) AS revenue')
            ->whereRaw('EXTRACT(YEAR FROM created_at) = ?', [now()->year])
            ->whereIn('status', ['confirmed', 'shipped', 'delivered'])
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('revenue', 'month')
            ->toArray();
            
        // Lấy hoạt động gần đây từ Orders và Users
        $recentOrders = Order::with('user')->latest()->take(5)->get()->map(function($order) {
            return [
                'title' => 'Đơn hàng mới #' . $order->id,
                'content' => 'Khách hàng <strong>' . ($order->user->username ?? 'Khách') . '</strong> vừa đặt hàng.',
                'time' => $order->created_at,
                'icon' => 'bx-shopping-bag',
                'class' => 'icon-orders'
            ];
        });

        $recentUsers = User::latest()->take(5)->get()->map(function($user) {
            return [
                'title' => 'Thành viên mới',
                'content' => 'Người dùng <strong>' . $user->username . '</strong> vừa đăng ký tài khoản.',
                'time' => $user->created_at,
                'icon' => 'bx-user-plus',
                'class' => 'icon-users'
            ];
        });

        $activities = $recentOrders->concat($recentUsers)->sortByDesc('time')->take(8);

        return view('Admin', compact('soldOrdersCount', 'revenue', 'customerCount', 'totalOrders', 'monthlyRevenue', 'activities'));
    }

    // Products
    public function indexProducts(Request $request)
    {
        $perPage = $request->input('per_page', 100);
        $products = Product::with(['brand', 'sizes'])->paginate($perPage);
        return response()->json($products);
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name', 
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'gender' => 'nullable|in:Men,Women,Unisex',
            'description' => 'nullable|string',
            'stock' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048'
        ]);

        $data = $request->only(['name', 'brand_id', 'price', 'gender', 'description', 'stock']);

        // Xử lý giá và số lượng
        $rawPrice = $request->input('price', 0);
        $data['price'] = (int) preg_replace('/[^\d]/', '', (string) $rawPrice);
        $data['stock'] = (int) $request->input('stock', 0);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        return response()->json(['message' => 'Sản phẩm đã được tạo thành công', 'data' => $product]);
    }

    // Cập nhật sản phẩm
    public function updateProduct(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', "unique:products,name,{$product->id}"],
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'gender' => 'nullable|in:Men,Women,Unisex',
            'description' => 'nullable|string',
            'stock' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048'
        ]);

        $data = $request->only(['name', 'brand_id', 'price', 'gender', 'description', 'stock']);
        $rawPrice = $request->input('price', 0);
        $data['price'] = (int) preg_replace('/[^\d]/', '', (string) $rawPrice);
        $data['stock'] = (int) $request->input('stock', 0);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return response()->json(['message' => 'Sản phẩm đã được cập nhật thành công', 'data' => $product]);
    }

    // Xóa sản phẩm
    public function destroyProduct($id)
    {
        try {
            $product = Product::findOrFail($id);
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->delete();
            return response()->json(['message' => 'Sản phẩm đã được xóa thành công']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
        }
    }

    // =================== PRODUCT SIZES CRUD ===================

    // Lấy danh sách sizes của sản phẩm
    public function indexSizes($productId)
    {
        $product = Product::with('sizes')->findOrFail($productId);
        return response()->json([
            'product_id'   => $product->id,
            'product_name' => $product->name,
            'sizes'        => $product->sizes,
        ]);
    }

    // Thêm size mới cho sản phẩm
    public function storeSize(Request $request, $productId)
    {
        $request->validate([
            'size'  => 'required|string|max:10',
            'stock' => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($productId);

        // Kiểm tra size đã tồn tại chưa
        if ($product->sizes()->where('size', $request->size)->exists()) {
            return response()->json(['message' => "Size {$request->size} đã tồn tại cho sản phẩm này."], 422);
        }

        $size = ProductSize::create([
            'product_id' => $product->id,
            'size'       => $request->size,
            'stock'      => $request->stock,
        ]);

        return response()->json(['message' => 'Đã thêm size thành công.', 'data' => $size]);
    }

    // Cập nhật stock / tên size
    public function updateSize(Request $request, $productId, $sizeId)
    {
        $request->validate([
            'size'  => 'sometimes|string|max:10',
            'stock' => 'required|integer|min:0',
        ]);

        $size = ProductSize::where('product_id', $productId)->findOrFail($sizeId);

        // Kiểm tra nếu đổi tên size có trùng không
        if ($request->has('size') && $request->size !== $size->size) {
            $exists = ProductSize::where('product_id', $productId)
                ->where('size', $request->size)
                ->where('id', '!=', $sizeId)
                ->exists();
            if ($exists) {
                return response()->json(['message' => "Size {$request->size} đã tồn tại."], 422);
            }
        }

        $size->update($request->only(['size', 'stock']));

        return response()->json(['message' => 'Đã cập nhật size thành công.', 'data' => $size]);
    }

    // Xóa size
    public function destroySize($productId, $sizeId)
    {
        $size = ProductSize::where('product_id', $productId)->findOrFail($sizeId);
        $size->delete();
        return response()->json(['message' => 'Đã xóa size thành công.']);
    }

    // Brands
    public function indexBrands(Request $request)
    {
        $perPage = $request->input('per_page', 100);
        $brands = Brand::withCount('products')->paginate($perPage);
        return response()->json($brands);
    }

    public function storeBrand(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands',
            'logo' => 'nullable|image|max:2048'
        ]);

        $data = ['name' => $request->name];

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $brand = Brand::create($data);

        return response()->json(['message' => 'Thương hiệu đã được tạo thành công', 'data' => $brand]);
    }

    // Cập nhật thương hiệu
    public function updateBrand(Request $request, $id)
    {
        try {
            $brand = Brand::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Thương hiệu không tồn tại'], 404);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', "unique:brands,name,{$brand->id}"],
            'logo' => 'nullable|image|max:2048'
        ]);

        $data = ['name' => $request->name];

        if ($request->hasFile('logo')) {
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $brand->update($data);

        return response()->json(['message' => 'Thương hiệu đã được cập nhật thành công', 'data' => $brand]);
    }

    // Xóa thương hiệu
    public function destroyBrand($id)
    {
        try {
            $brand = Brand::findOrFail($id);
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $brand->delete();
            return response()->json(['message' => 'Thương hiệu đã được xóa thành công']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Thương hiệu không tồn tại'], 404);
        }
    }

    // Orders
    public function indexOrders(Request $request)
    {
        $perPage = $request->input('per_page', 100);
        $orders = Order::with(['user', 'items.product'])->orderBy('created_at', 'desc')->paginate($perPage);
        return response()->json($orders);
    }

    // Cập nhật trạng thái đơn hàng
    public function updateOrderStatus(Order $order, Request $request)
    {
        $vietnameseToEnglish = [
            'Chờ xử lý' => 'pending',
            'Đã xác nhận' => 'confirmed',
            'Đang giao' => 'shipped',
            'Đã giao' => 'delivered',
            'Đã hủy' => 'cancelled'
        ];

        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys($vietnameseToEnglish))
        ]);

        $englishStatus = $vietnameseToEnglish[$request->status] ?? $request->status;
        $order->update(['status' => $englishStatus]);

        return response()->json(['message' => 'Trạng thái đã được cập nhật thành công']);
    }

    // Xóa đơn hàng
    public function destroyOrder(Order $order)
    {
        $order->items()->delete();
        $order->delete();

        return response()->json(['message' => 'Đơn hàng đã được xóa thành công']);
    }

    // Settings
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Mật khẩu hiện tại không đúng'], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Mật khẩu đã được thay đổi thành công']);
    }

    // Xuất danh sách đơn hàng ra file Excel
    public function exportOrders()
    {
        $orders = Order::with(['user', 'items.product'])->get();
        return Excel::download(new OrdersExport($orders), 'don-hang-' . now()->format('Y-m-d') . '.xlsx');
    }

    // Xóa toàn bộ dữ liệu
    public function clearData(Request $request)
    {
        if (!$request->isMethod('delete')) {
            abort(405, 'Method Not Allowed');
        }

        Order::truncate();
        OrderItem::truncate();
        Product::truncate();
        Brand::truncate();
        User::where('role', '!=', 'admin')->delete();

        return response()->json(['message' => 'Dữ liệu đã được xóa thành công']);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Routing\Controller;

class AdminOrderController extends Controller
{
    // Kiểm tra quyền admin
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    // Hiển thị danh sách đơn hàng
    public function index(Request $request)
    {
        $orders = Order::with(['user', 'items'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderBy('created_at', 'desc');

        if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
            $result = $orders->get();
            return response()->json(['data' => $result]);
        }

        $orders = $orders->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    // Cập nhật trạng thái đơn hàng
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:chờ xử lý,đã xác nhận,đang giao,đã giao,đã hủy',
        ]);

        $statusMap = [
            'chờ xử lý' => 'pending',
            'đã xác nhận' => 'confirmed',
            'đang giao' => 'shipped',
            'đã giao' => 'delivered',
            'đã hủy' => 'cancelled'
        ];

        $oldStatus = $order->status;
        $newStatus = $statusMap[$request->status] ?? $request->status;

        // Cập nhật trạng thái (Dùng English key để đồng bộ toàn hệ thống)
        /** @var \App\Models\Order $order */
        $order->update(['status' => $newStatus]);

        $stockUpdates = [];

        // Trạng thái active (đã trừ kho)
        $activeStatuses = ['confirmed', 'shipped', 'delivered'];

        // 1. Trừ kho: Nếu chuyển từ 'pending' sang bất kỳ trạng thái active nào
        $shouldDeductStock = ($oldStatus === 'pending') && in_array($newStatus, $activeStatuses);

        // 2. Hoàn kho: Nếu chuyển từ trạng thái active về 'cancelled'
        $shouldRestoreStock = in_array($oldStatus, $activeStatuses) && ($newStatus === 'cancelled');

        if ($shouldDeductStock || $shouldRestoreStock) {
            $order->load('items');

            foreach ($order->items as $item) {
                // Ưu tiên tìm theo product_id
                $product = null;
                if ($item->product_id) {
                    $product = Product::query()->where('id', '=', $item->product_id)->first();
                }
                
                if (!$product) {
                    $product = Product::query()->where('name', '=', $item->product_name)->first();
                }

                if (!$product instanceof Product) continue;

                // Tìm sizeRecord
                $sizeRecord = ProductSize::query()
                    ->where('product_id', '=', $product->id)
                    ->where('size', '=', $item->size)
                    ->first();

                if ($sizeRecord instanceof ProductSize) {
                    $qty = $item->quantity ?? 1;
                    $oldQty = $sizeRecord->stock;

                    if ($shouldDeductStock) {
                        $newStock = max(0, $oldQty - $qty);
                    } else {
                        $newStock = $oldQty + $qty;
                    }

                    $sizeRecord->update(['stock' => $newStock]);

                    $stockUpdates[] = [
                        'product_name' => $product->name,
                        'size'         => $item->size,
                        'action'       => $shouldDeductStock ? 'Deduct' : 'Restore',
                        'old_stock'    => $oldQty,
                        'new_stock'    => $newStock,
                    ];
                }
            }
        }

        return response()->json([
            'success'       => true,
            'message'       => 'Cập nhật trạng thái thành công!' . ($stockUpdates ? ' Hệ thống đã cập nhật lại kho hàng.' : ''),
            'data'          => $order->load('user'),
            'stock_updates' => $stockUpdates,
        ]);
    }

    // Xóa đơn hàng
    public function destroy(Request $request, Order $order)
    {
        // Sử dụng query builder để tránh lỗi nhận diện sai tham số từ IDE
        Order::query()->where('id', '=', $order->id)->delete();

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa đơn hàng thành công!'
            ]);
        }

        return redirect()->route('admin.orders.index')->with('success', 'Xóa đơn hàng thành công!');
    }

    // Hiển thị chi tiết đơn hàng
    public function show(Order $order)
    {
        $order->load('items', 'user');
        return view('admin.orders.show', compact('order'));
    }

    // Xuất danh sách đơn hàng ra file Excel
    public function export(Request $request)
    {
        $orders = Order::with('user')->get();
        $filename = 'don-hang-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Khách Hàng', 'Tổng Tiền', 'Trạng Thái', 'Ngày Tạo']);
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->user->name ?? 'N/A',
                    number_format((float)$order->total) . ' ₫',
                    ucfirst($order->status),
                    $order->created_at->format('d/m/Y H:i')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
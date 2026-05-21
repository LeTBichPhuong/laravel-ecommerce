<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function send(Request $request)
    {
        $msg = trim($request->message);

        // Lấy danh sách thương hiệu để cung cấp context cho Bot
        $brandList = \App\Models\Brand::pluck('name')->implode(', ');

        if (!$msg) {
            return response()->json(['reply' => 'Bạn chưa nhập nội dung nào']);
        }

        // Rate limiting
        $ip = $request->ip();
        $key = "chatbot_limit_{$ip}";
        
        if (Cache::has($key) && Cache::get($key) >= 5) {
            return response()->json([
                'reply' => 'Bạn đang gửi tin nhắn quá nhanh. Vui lòng đợi 1 phút.'
            ]);
        }

        Cache::put($key, Cache::get($key, 0) + 1, now()->addMinute());

        try {
            $apiKey = env('GROQ_API_KEY');
            
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.1-8b-instant',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => "Bạn là 'Luma Shoes Bot' — Trợ lý ảo cao cấp của cửa hàng giày Luma Shoes. 
                            
                            THÔNG TIN CỬA HÀNG:
                            - Địa chỉ: CS1: Hào Nam, Ô Chợ Dừa, TP. Hà Nội. 📍
                            - Hotline: 0386759355. 📞
                            - Giờ mở cửa: 8h30 - 21h00 hàng ngày. ⏰
                            - Website: http://127.0.0.1:8000
                            
                            CHÍNH SÁCH VÀ CAM KẾT: ✅
                            - 100% Authentic: Cam kết hàng chính hãng, phát hiện hàng giả đền bù gấp 10 lần giá trị. 💯
                            - Giao hàng: Miễn phí vận chuyển toàn quốc cho các hóa đơn mua giày. 🚚
                            - Ưu đãi: Khách hàng thân thiết được giảm từ 5% đến 15% cho đơn hàng tiếp theo. 🎁
                            - Thương hiệu hiện có: {$brandList}.
                            
                            HƯỚNG DẪN TRÌNH BÀY (QUAN TRỌNG):
                            1. Sử dụng Markdown: In đậm tên thương hiệu, giá tiền hoặc thông tin quan trọng bằng dấu ** (Ví dụ: **Nike**, **1.200.000đ**).
                            2. Chia nhỏ nội dung: Luôn dùng dấu gạch đầu dòng (-) hoặc số (1, 2) cho danh sách. Xuống dòng giữa các đoạn văn để tạo độ thoáng.
                            3. Sử dụng Emojis: Thêm icon phù hợp để câu trả lời sinh động nhưng vẫn rất cao cấp.
                            4. Giọng điệu: Sang trọng, lễ phép. Bắt đầu bằng 'Dạ' và kết thúc bằng một lời chúc chân thành.
                            
                            LƯU Ý: Nếu không biết chắc chắn, hãy bảo khách hàng liên hệ hotline 0386759355 để được nhân viên hỗ trợ trực tiếp."
                        ],
                        [
                            'role' => 'user',
                            'content' => $msg
                        ]
                    ],
                    'max_tokens' => 800,
                    'temperature' => 0.6,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['choices'][0]['message']['content'] ?? 'Xin lỗi, tôi không thể trả lời lúc này';
                return response()->json(['reply' => trim($reply)]);
            }

            // Log lỗi để debug nếu cần
            Log::error('Groq API Error: ' . $response->status() . ' - ' . $response->body());

            return response()->json(['reply' => 'Hệ thống đang bảo trì một chút, anh/chị vui lòng thử lại sau giây lát ạ!']);

        } catch (\Exception $e) {
            Log::error('Chatbot Exception: ' . $e->getMessage());
            return response()->json(['reply' => 'Dạ, có lỗi kết nối xảy ra. Anh/chị thử lại giúp em nhé!']);
        }
    }
}
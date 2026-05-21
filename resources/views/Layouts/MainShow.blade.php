@extends('Product')

@push('MasterCSS')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap');

        :root {
            --p-red: #e61e27;
            --p-black: #333333;
            --p-grey: #666;
            --p-border: #eeeeee;
            --p-green: #2ecc71;
            --p-blue: #3498db;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f7f7f7;
            color: var(--p-black);
            margin: 0;
            overflow-x: hidden;
        }

        .p-main-detail {
            margin-top: 80px;
            /* Offset for fixed header */
            padding-bottom: 60px;
        }

        .p-wrapper {
            max-width: 1060px;
            margin: 20px auto 40px;
            background: #fff;
            padding: 25px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
            border: 1px solid #eee;
        }

        /* --- Breadcrumb --- */
        .p-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.7rem;
            color: #999;
            margin-bottom: 20px;
        }

        .p-breadcrumb a {
            color: inherit;
            text-decoration: none;
        }

        .p-breadcrumb .sep {
            opacity: 0.5;
        }

        /* --- Grid --- */
        .p-grid {
            display: grid;
            grid-template-columns: 460px 1fr;
            /* Fixed gallery width to keep image small */
            gap: 40px;
        }

        /* --- Left: Gallery --- */
        .p-gallery {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .p-main-view {
            width: 100%;
            height: 460px;
            border: 1px solid var(--p-border);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: #fff;
        }

        .p-main-view img {
            max-width: 80%;
            /* Shrink image inside viewport */
            max-height: 80%;
            object-fit: contain;
        }

        .p-view-nav {
            position: absolute;
            right: -20px;
            top: 50%;
            transform: translateY(-50%);
            width: 36px;
            height: 36px;
            background: #fff;
            border: 1px solid var(--p-border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            z-index: 5;
            font-size: 1.2rem;
        }

        .p-thumbs {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .p-thumb {
            width: 60px;
            height: 60px;
            border: 1px solid var(--p-border);
            cursor: pointer;
            padding: 4px;
            flex-shrink: 0;
            background: #fff;
        }

        .p-thumb.active {
            border-color: #333;
        }

        .p-thumb img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* --- Right: Content --- */
        .p-details {
            display: flex;
            flex-direction: column;
        }

        .p-title-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .p-title {
            font-size: 1.35rem;
            font-weight: 700;
            margin: 0;
            color: #222;
        }

        .p-status {
            background: var(--p-green);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 2px;
        }

        .p-authentic {
            color: var(--p-blue);
            font-size: 0.72rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
            margin-bottom: 12px;
        }

        .p-meta {
            font-size: 0.78rem;
            color: #777;
            margin-bottom: 20px;
        }

        .p-meta span {
            color: #333;
            font-weight: 500;
        }

        .p-meta .brand-link {
            color: var(--p-red);
            font-weight: 600;
        }

        .p-meta .sep {
            margin: 0 8px;
            color: #eee;
        }

        .p-price-row {
            margin-bottom: 20px;
            display: flex;
            align-items: baseline;
            gap: 10px;
        }

        .p-price-new {
            font-size: 1.5rem;
            font-weight: 900;
            color: var(--p-red);
        }

        .p-price-old {
            font-size: 0.95rem;
            color: #bbb;
            text-decoration: line-through;
        }

        /* --- Selectors --- */
        .p-sel-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .p-guide {
            color: #333;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 4px;
            font-weight: 700;
        }

        .p-size-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 25px;
        }

        .p-size-item {
            width: 42px;
            height: 36px;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.78rem;
            font-weight: 500;
            cursor: pointer;
            border-radius: 2px;
            transition: 0.2s;
        }

        .p-size-item:hover {
            border-color: var(--p-red);
        }

        .p-size-item.active {
            border-color: var(--p-red);
            color: var(--p-red);
            border-width: 2px;
        }

        /* --- Actions --- */
        .p-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
        }

        .p-qty {
            display: flex;
            border: 1px solid #ddd;
            border-radius: 2px;
            overflow: hidden;
            height: 44px;
        }

        .p-qty-btn {
            width: 34px;
            border: none;
            background: #fff;
            font-size: 1.1rem;
            cursor: pointer;
            color: #666;
        }

        .p-qty-val {
            width: 36px;
            border: none;
            text-align: center;
            font-weight: 700;
            font-size: 0.9rem;
            color: #333;
        }

        .p-btn {
            height: 44px;
            border-radius: 2px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
        }

        .p-btn-cart {
            flex: 1.4;
            background: #444;
            color: #fff;
            border: none;
        }

        .p-btn-cart:hover {
            background: #333;
        }

        .p-btn-buy {
            flex: 1;
            background: #fff;
            border: 1px solid #ccc;
            color: #444;
        }

        .p-btn-buy:hover {
            background: #f5f5f5;
            border-color: #999;
        }

        /* --- Separate Features Box --- */
        .p-features-box {
            border: 1px solid #f0f0f0;
            padding: 25px 15px;
            border-radius: 4px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 10px;
        }

        .p-feat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 10px;
        }

        .p-feat-icon-pos {
            position: relative;
            width: 50px;
            height: 50px;
        }

        .p-feat-icon-pos i {
            font-size: 2rem !important;
            color: #b00000 !important;
            width: 100%;
            height: 100%;
            display: flex !important;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 0;
            left: 0;
        }

        .p-feat-num {
            position: absolute;
            top: -4px;
            left: -4px;
            width: 20px;
            height: 20px;
            background: #c0392b;
            color: #fff;
            border-radius: 50%;
            font-size: 0.6rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .p-feat-num.orange {
            background: #d35400;
        }

        .p-feat-num.purple {
            background: #8e44ad;
        }

        .p-feat-label {
            font-size: 0.72rem;
            font-weight: 700;
            line-height: 1.4;
            color: #333;
        }

        /* --- Description & Related --- */
        .p-bottom-content {
            max-width: 1060px;
            margin: 0 auto 60px;
        }

        .p-section-bar {
            background: #fff;
            padding: 12px 20px;
            border-left: 4px solid var(--p-red);
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            margin: 30px 0 15px;
            border: 1px solid #eee;
            border-left-width: 4px;
        }

        .p-tabs {
            display: flex;
            border: 1px solid #eee;
            background: #fff;
        }

        .p-tab-trigger {
            flex: 1;
            padding: 12px;
            border: none;
            background: none;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            cursor: pointer;
            border-bottom: 2px solid transparent;
        }

        .p-tab-trigger.active {
            color: var(--p-red);
            border-bottom-color: var(--p-red);
        }

        .p-panel {
            background: #fff;
            padding: 20px;
            border: 1px solid #eee;
            border-top: none;
            display: none;
            font-size: 0.9rem;
            line-height: 1.7;
        }

        .p-panel.active {
            display: block;
        }

        .p-rel-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 15px;
        }

        .p-rel-card {
            background: #fff;
            border: 1px solid #eee;
            padding: 12px;
            text-align: center;
            transition: 0.3s;
        }

        .p-rel-card:hover {
            border-color: var(--p-red);
            transform: translateY(-3px);
        }

        .p-rel-img {
            height: 130px;
            margin-bottom: 10px;
        }

        .p-rel-img img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .p-rel-name {
            font-size: 0.8rem;
            font-weight: 500;
            height: 36px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .p-rel-price {
            color: var(--p-red);
            font-weight: 700;
            font-size: 0.85rem;
        }

        /* layout sản phẩm mới */
        .main-section.layout-grid {
            max-width: 1080px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .section-header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .title-left {
            font-size: 1.1rem;
            font-weight: 800;
            margin: 0;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: 1px;
        }

        .title-left::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 22px;
            background: #111;
        }

        .grid-container .slider {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            padding: 5px 5px 15px;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
        }

        .grid-container .slider::-webkit-scrollbar {
            height: 4px;
        }

        .grid-container .slider::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .grid-container .slider::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }

        .grid-container .slider::-webkit-scrollbar-thumb:hover {
            background: #b00000;
        }

        .grid-container .product {
            flex: 0 0 190px;
            background: #fff;
            padding: 10px;
            border: 1px solid #f0f0f0;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            gap: 10px;
            scroll-snap-align: start;
        }

        .grid-container .product:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .product-img-wrapper {
            position: relative;
            background: #f9f9f9;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-radius: 4px;
        }

        .product-img-wrapper img {
            max-width: 85%;
            max-height: 85%;
            object-fit: contain;
            transition: transform 0.5s ease;
        }

        .grid-container .product:hover .product-img-wrapper img {
            transform: scale(1.1);
        }

        .badge-new {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #111;
            color: #fff;
            font-size: 0.6rem;
            font-weight: 900;
            padding: 3px 8px;
            z-index: 2;
            border-radius: 2px;
        }

        .btn-quick-action {
            position: absolute;
            bottom: 25px;
            right: 10px;
            width: 32px;
            height: 32px;
            background: #444;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            opacity: 1;
            color: #fff;
        }

        .btn-quick-action:hover,
        .btn-quick-action:active {
            background: #fff;
            color: #111;
        }

        .grid-container .product:hover .btn-quick-action {
            bottom: 10px;
            opacity: 1;
        }

        .grid-container .product p {
            font-size: 0.72rem;
            font-weight: 600;
            color: #333;
            margin: 0;
            line-height: 1.4;
            height: 34px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .price-and-badge {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }

        .discounted-price {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--p-red);
        }

        .badge-out-stock {
            font-size: 0.55rem;
            font-weight: 700;
            color: #fff;
            background: var(--p-green);
            padding: 2px 5px;
            border-radius: 2px;
        }

        @media (max-width: 1024px) {
            .p-grid {
                grid-template-columns: 1fr;
            }

            .p-wrapper {
                margin: 20px 10px;
                padding: 15px;
            }

            .p-main-view {
                height: 350px;
            }

            .grid-container .slider {
                gap: 10px;
            }

            .grid-container .product {
                flex: 0 0 160px;
            }
        }
    </style>
@endpush

@section('content')
    <main class="p-main-detail" style="min-height: 100vh; padding-bottom: 80px;">
        <div class="p-wrapper">
            {{-- Breadcrumb --}}
            <nav class="p-breadcrumb">
                <a href="{{ route('home') }}">Trang chủ</a>
                <span class="sep">/</span>
                <a href="#">{{ $product->brand->name ?? 'Sneaker' }}</a>
                <span class="sep">/</span>
                <span>{{ $product->name }}</span>
            </nav>

            <div class="p-grid">
                {{-- ảnh sản phẩm --}}
                <div class="p-gallery">
                    <div class="p-main-view">
                        <img src="{{ $product->image ?? asset('img/placeholder-product.png') }}" id="main-img"
                            alt="{{ $product->name }}">
                        <div class="p-view-nav" onclick="nextImg()"><i class='bx bx-chevron-right'></i></div>
                    </div>
                    <div class="p-thumbs">
                        @for ($i = 0; $i < 6; $i++)
                            <div class="p-thumb {{ $i == 0 ? 'active' : '' }}"
                                onclick="switchImg(this, '{{ $product->image ?? asset('img/placeholder-product.png') }}')">
                                <img src="{{ $product->image ?? asset('img/placeholder-product.png') }}" alt="thumb">
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- Chi tiết sản phẩm --}}
                <div class="p-details">
                    <div class="p-title-row">
                        <h1 class="p-title">{{ $product->name }}</h1>
                        <span class="p-status">Còn Hàng</span>
                    </div>

                    <div class="p-authentic">
                        <i class='bx bxs-check-circle'></i>
                        <span>Authentic 100%</span>
                    </div>

                    <div class="p-meta">
                        Thương hiệu: <a href="#" class="brand-link">{{ $product->brand->name ?? 'Nike' }}</a>
                        <span class="sep">|</span>
                        Loại: <span>Hàng Có Sẵn</span>
                        <span class="sep">|</span>
                        MSP: <span
                            style="color:var(--p-red);">{{ str_replace(' ', '', $product->name) }}-{{ $product->id }}</span>
                    </div>

                    <div class="p-price-row">
                        <span class="p-price-new">{{ \App\Helpers\helpers::format($product->price) }}</span>
                        <span class="p-price-old">3,050,000đ</span>
                    </div>

                    <form action="{{ route('cart.add', $product->id) }}" method="POST" id="buy-form">
                        @csrf
                        <input type="hidden" name="size" id="size-val" value="">
                        <input type="hidden" name="quantity" id="qty-val" value="1">

                        <div class="p-sel-header">
                            <span>Kích thước:</span>
                            <a href="#" class="p-guide"><i class='bx bx-ruler'></i> Hướng dẫn chọn size</a>
                        </div>

                        <div class="p-size-grid">
                            @forelse($product->sizes as $sz)
                                <div class="p-size-item" onclick="selectSize(this, '{{ $sz->size }}')">
                                    {{ $sz->size }}
                                </div>
                            @empty
                                <p style="font-size:0.75rem; color:#999;">Liên hệ để xem size...</p>
                            @endforelse
                        </div>

                        <div class="p-actions">
                            <div class="p-qty">
                                <button type="button" class="p-qty-btn" onclick="updateQty(-1)">−</button>
                                <input type="text" value="1" id="qty-box" class="p-qty-val" readonly>
                                <button type="button" class="p-qty-btn" onclick="updateQty(1)">+</button>
                            </div>
                            <button type="submit" class="p-btn p-btn-cart">THÊM VÀO GIỎ</button>
                            <button type="button" class="p-btn p-btn-buy" onclick="directBuy()">MUA NGAY</button>
                        </div>
                    </form>

                    {{-- thông tin thêm --}}
                    <div class="p-features-box">
                        <div class="p-feat-item">
                            <div class="p-feat-icon-pos">
                                <i class="bx bx-package"></i>
                            </div>
                            <div class="p-feat-label">Đóng gói cẩn thận<br>double box</div>
                        </div>
                        <div class="p-feat-item">
                            <div class="p-feat-icon-pos">
                                <i class="bx bx-refresh"></i>
                            </div>
                            <div class="p-feat-label">Miễn phí đổi hàng<br>trong 07 ngày</div>
                        </div>
                        <div class="p-feat-item">
                            <div class="p-feat-icon-pos">
                                <i class='bx bxs-truck'></i>
                            </div>
                            <div class="p-feat-label">Giao hàng nhanh<br>toàn quốc</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- thông tin sản phẩm --}}
        <div class="p-bottom-content">
            <div class="p-tabs">
                <button class="p-tab-trigger active" data-tab="desc">MÔ TẢ</button>
                <button class="p-tab-trigger" data-tab="ship">CHÍNH SÁCH THANH TOÁN</button>
                <button class="p-tab-trigger" data-tab="ret">CHÍNH SÁCH ĐỔI TRẢ</button>
            </div>

            <div class="p-panel active" id="desc">
                <h3 style="font-size: 1rem; color: #3498db; margin-bottom: 15px;">Giày
                    {{ $product->brand->name ?? 'Nike' }}
                    <span style="color: #333;">{{ $product->name }}</span>
                </h3>
                <p style="margin-bottom: 20px;">
                    {{ $product->name }} – giày trắng thì có gì thú vị? Đầu tiên, bạn sẽ tha hồ biến hóa cùng các loại
                    quần áo, phụ kiện đa dạng để cho ra một outfit ưng ý nhất. Mặt khác, đây lại là đôi giày lý tưởng vào
                    một ngày bạn cảm thấy quá “lười” để dành thời gian lựa chọn, cân nhắc giữa cả chồng trang phục. Và cuối
                    cùng, một đôi giày đơn giản nhưng không đơn điệu, cho cảm giác trên chân êm ái, thoải mái vận động, có
                    lẽ là ứng cử viên sáng giá để bạn bổ sung vào tủ đồ của mình.
                </p>
                <ul style="list-style: none; padding: 0; line-height: 2;">
                    <li><b>Thương hiệu:</b> {{ $product->brand->name ?? 'Nike' }}</li>
                    <li><b>Mã sản phẩm:</b> {{ str_replace(' ', '', $product->name) }}-{{ $product->id }}</li>
                    <li><b>Chất liệu:</b> Da cao cấp</li>
                    <li><b>Xuất Xứ:</b> Mỹ</li>
                    <li><b>Tình trạng:</b> Hàng Fullbox - New 100%</li>
                </ul>
                <p style="margin-top: 20px; font-style: italic;">
                    <u>Lưu ý:</u> Đối với các sản phẩm hết hàng sẵn hoặc hết size bạn cần, Quý khách có thể liên hệ với Luma
                    Shoes để sử dụng dịch vụ <span style="color: #3498db;">ORDER</span> sản phẩm của chúng tôi.
                </p>
            </div>

            <div class="p-panel" id="ship">
                <div style="line-height: 1.8;">
                    <h4 style="margin-bottom: 10px;">1. Giới thiệu</h4>
                    <p>Chào mừng quý khách hàng đến với website của Luma Shoes</p>
                    <p>Khi quý khách hàng truy cập vào trang website của chúng tôi có nghĩa là quý khách đồng ý với các điều
                        khoản này. Trang web có quyền thay đổi, chỉnh sửa, thêm hoặc lược bỏ bất kỳ phần nào trong Điều
                        khoản mua bán hàng hóa này, vào bất cứ lúc nào. Các thay đổi có hiệu lực ngay khi được đăng trên
                        trang web mà không cần thông báo trước. Và khi quý khách tiếp tục sử dụng trang web, sau khi các
                        thay đổi về Điều khoản này được đăng tải, có nghĩa là quý khách chấp nhận với những thay đổi đó.</p>
                    <p>Quý khách hàng vui lòng kiểm tra thường xuyên để cập nhật những thay đổi của chúng tôi.</p>

                    <h4 style="margin: 20px 0 10px;">2. Hướng dẫn sử dụng website</h4>
                    <p>Khi vào web của chúng tôi, khách hàng phải đảm bảo đủ 18 tuổi, hoặc truy cập dưới sự giám sát của cha
                        mẹ hay người giám hộ hợp pháp. Khách hàng đảm bảo có đầy đủ hành vi dân sự để thực hiện các giao
                        dịch mua bán hàng hóa theo quy định hiện hành của pháp luật Việt Nam.</p>
                    <p>Trong suốt quá trình đăng ký, quý khách đồng ý nhận email quảng cáo từ website. Nếu không muốn tiếp
                        tục nhận mail, quý khách có thể từ chối bằng cách nhấp vào đường link ở dưới cùng trong mọi email
                        quảng cáo.</p>

                    <h4 style="margin: 20px 0 10px;">3. Thanh toán an toàn và tiện lợi</h4>
                    <p>Người mua có thể tham khảo các phương thức thanh toán sau đây và lựa chọn áp dụng phương thức phù
                        hợp:</p>
                    <ul style="padding-left: 20px;">
                        <li>Cách 1: Thanh toán trực tiếp (người mua nhận hàng tại địa chỉ cửa hàng)</li>
                        <li>Cách 2: Thanh toán sau (COD – giao hàng và thu tiền tận nơi)</li>
                        <li>Cách 3: Thanh toán online qua chuyển khoản.</li>
                    </ul>
                </div>
            </div>

            <div class="p-panel" id="ret">
                <div style="line-height: 1.8; font-size: 0.9rem;">
                    <p>Lumashoes.vn luôn trân trọng sự tín nhiệm của quý khách giành cho chúng tôi. Chính vì vậy, chúng tôi
                        luôn cố gắng để mang đến quý khách hàng những sản phẩm chất lượng cao và tiết kiệm chi phí.</p>
                    <p>Thay cho cam kết về chất lượng sản phẩm, Lumashoes.vn thực hiện chính sách đổi trả hàng hóa. Theo đó,
                        tất cả các sản phẩm được mua tại Lumashoes.vn đều có thể đổi size và mẫu trong vòng 07 ngày sau khi
                        nhận hàng.</p>
                    <p>Để được thực hiện đổi hàng hoá, Quý khách cần giữ lại Hóa đơn mua hàng tại Lumashoes.vn. Sản phẩm
                        được đổi là những sản phẩm đáp ứng được những điều kiện trong Chính sách đổi trả hàng hóa.</p>
                    <p>Lumashoes.vn thực hiện đổi hàng/trả lại tiền cho Quý khách, nhưng không hoàn lại phí vận chuyển hoặc
                        lệ phí giao hàng, trừ những trường hợp sau:</p>
                    <ul style="padding-left: 20px;">
                        <li>Không đúng chủng loại, mẫu mã như quý khách đặt hàng.</li>
                        <li>Tình trạng bên ngoài bị ảnh hưởng như bong tróc, bể vỡ xảy ra trong quá trình vận chuyển,…</li>
                        <li>Không đạt chất lượng như: phát hiện hàng fake, hàng kém chất lượng, không phải hàng chính hãng
                        </li>
                    </ul>
                    <p>Quý khách vui lòng kiểm tra hàng hóa và ký nhận tình trạng với nhân viên giao hàng ngay khi nhận được
                        hàng. Khi phát hiện một trong các trường hợp trên, quý khách có thể trao đổi trực tiếp với nhân viên
                        giao hàng hoặc phản hồi cho chúng tôi trong vòng 24h theo số Hotline : 084. 850. 6666</p>

                    <p style="margin-top: 15px; font-weight: bold;">Lumashoes.vn sẽ không chấp nhận đổi/trả hàng khi:</p>
                    <ul style="padding-left: 20px;">
                        <li>Hàng hoá là hàng order</li>
                        <li>Thời điểm thông báo đổi trả quá 07 ngày kể từ khi Quý khách nhận hàng</li>
                        <li>Quý khách tự làm ảnh hưởng tình trạng bên ngoài như rách bao bì, bong tróc, bể vỡ, bị bẩn, hư
                            hại (không còn như nguyên vẹn ban đầu),...</li>
                        <li>Quý khách vận hành không đúng chỉ dẫn gây hỏng hóc hàng hóa.</li>
                        <li>Quý khách đã kiểm tra và ký nhận tình trạng hàng hóa nhưng không có phản hồi trong vòng 24h kể
                            từ lúc ký nhận hàng.</li>
                        <li>Không còn size/ mẫu mà khách hàng muốn đổi.</li>
                        <li>Không đổi từ hàng hóa có sẵn sang hàng phải order.</li>
                        <li>Sản phẩm đã cắt tag/mác.</li>
                        <li>Sản phẩm đã qua sử dụng.</li>
                    </ul>

                    <p style="margin-top: 15px; font-weight: bold;">Luma Shoes thực hiện đổi trả theo quy trình sau:</p>
                    <ul style="list-style: none; padding: 0;">
                        <li><b>Bước 1:</b> Quý khách liên hệ trực tiếp với Luma Shoes qua số Hotline : 038. 675. 9355 để
                            thông báo tình trạng hàng hoá cần đổi/trả trong vòng 07 ngày kể từ khi nhận hàng.</li>
                        <li><b>Bước 2:</b> Nhân viên Luma Shoes sẽ tiếp nhận phản hồi và hướng dẫn bạn cung cấp thông tin
                            đơn hàng để chúng tôi truy soát.</li>
                        <li><b>Bước 3:</b> Quý khách ship hàng cần đổi/ trả kèm hoá đơn lại về địa chỉ của Luma Shoes để
                            chúng tôi kiểm tra.</li>
                        <li><b>Bước 4:</b> Sau khi kiểm tra hàng và xác nhận đủ sản phẩm đủ điều kiện đổi/trả, Luma Shoes sẽ
                            liên hệ lại xác nhận với bạn và gửi hàng về cho bạn theo địa chỉ bạn cung cấp.</li>
                    </ul>
                    <p style="margin-top: 10px; font-weight: bold; color: #e61e27;">Lưu ý: Quý khách sẽ phải chịu phí ship
                        2 chiều khi đổi/trả. Chỉ hỗ trợ đổi sản phẩm một lần duy nhất.</p>
                </div>
            </div>

            {{-- sp liên quan --}}
            <section class="main-section layout-grid" style="margin-top: 60px;">
                <div class="section-header-flex">
                    <h2 class="title-left">SẢN PHẨM LIÊN QUAN</h2>
                </div>
                <div class="grid-container">
                    <div class="slider active">
                        @foreach ($featuredProducts->take(10) as $item)
                            <div class="product">
                                <a href="{{ route('products.show', $item->id) }}" style="text-decoration:none;">
                                    <div class="product-img-wrapper">
                                        <div class="badge-new">NEW</div>
                                        <img src="{{ $item->image ?? asset('img/placeholder-product.png') }}"
                                            alt="{{ $item->name }}">
                                        <div class="btn-quick-action"><i class='bx bx-shopping-bag'></i></div>
                                    </div>
                                </a>
                                <a href="{{ route('products.show', $item->id) }}" style="text-decoration:none;">
                                    <p>{{ $item->name }}</p>
                                </a>
                                <div class="price-and-badge">
                                    <span class="discounted-price">{{ \App\Helpers\helpers::format($item->price) }}</span>
                                    <span class="badge-out-stock">Còn hàng</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- sản phẩm đã xem --}}
            <section class="main-section layout-grid" id="sec-recent" style="display:none; margin-top: 40px;">
                <div class="section-header-flex">
                    <h2 class="title-left">SẢN PHẨM ĐÃ XEM</h2>
                </div>
                <div class="grid-container">
                    <div class="slider active" id="recent-grid"></div>
                </div>
            </section>
        </div>
    </main>

    @push('scripts')
        <script>
            function switchImg(el, src) {
                document.getElementById('main-img').src = src;
                document.querySelectorAll('.p-thumb').forEach(x => x.classList.remove('active'));
                el.classList.add('active');
            }

            function selectSize(el, s) {
                document.querySelectorAll('.p-size-item').forEach(x => x.classList.remove('active'));
                el.classList.add('active');
                document.getElementById('size-val').value = s;
            }

            function updateQty(d) {
                const b = document.getElementById('qty-box');
                const h = document.getElementById('qty-val');
                let v = parseInt(b.value) + d;
                if (v < 1) v = 1;
                b.value = v;
                h.value = v;
            }
            document.querySelectorAll('.p-tab-trigger').forEach(btn => {
                btn.addEventListener('click', () => {
                    const tid = btn.getAttribute('data-tab');
                    document.querySelectorAll('.p-tab-trigger').forEach(b => b.classList.remove('active'));
                    document.querySelectorAll('.p-panel').forEach(p => p.classList.remove('active'));
                    btn.classList.add('active');
                    document.getElementById(tid).classList.add('active');
                });
            });

            function trackHistory() {
                const p = {
                    id: "{{ $product->id }}",
                    name: "{{ $product->name }}",
                    price: "{{ \App\Helpers\helpers::format($product->price) }}",
                    img: "{{ $product->image ?? asset('img/placeholder-product.png') }}",
                    url: window.location.href
                };
                let list = JSON.parse(localStorage.getItem('shoes_history') || '[]');
                list = list.filter(x => x.id !== p.id);
                list.unshift(p);
                localStorage.setItem('shoes_history', JSON.stringify(list.slice(0, 10)));
                const others = list.filter(x => x.id !== p.id);
                if (others.length > 0) {
                    document.getElementById('sec-recent').style.display = 'block';
                    document.getElementById('recent-grid').innerHTML = others.map(x => `
                        <div class="product">
                            <a href="${x.url}" style="text-decoration:none;">
                                <div class="product-img-wrapper">
                                    <div class="badge-new">NEW</div>
                                    <img src="${x.img}" alt="${x.name}">
                                    <div class="btn-quick-action"><i class="bx bx-shopping-bag"></i></div>
                                </div>
                            </a>
                            <a href="${x.url}" style="text-decoration:none;">
                                <p>${x.name}</p>
                            </a>
                            <div class="price-and-badge">
                                <span class="discounted-price">${x.price}</span>
                                <span class="badge-out-stock">Còn hàng</span>
                            </div>
                        </div>
                    `).join('');
                }
            }

            function directBuy() {
                const s = document.getElementById('size-val').value;
                if (!s) return alert('Vui lòng chọn size!');
                document.getElementById('buy-form').submit();
            }
            document.addEventListener('DOMContentLoaded', trackHistory);
        </script>
    @endpush
@endsection

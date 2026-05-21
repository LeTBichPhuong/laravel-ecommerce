@extends('Checkout')

@section('checkout')
    <style>
        :root {
            --primary-color: #2c2c2c;
            --accent-color: #000;
            --bg-gray: #f9f9f9;
            --border-color: #e5e7eb;
            --success-green: #27ae60;
        }

        .checkout-wrapper {
            background-color: var(--bg-gray);
            margin-top: 100px;
            padding: 25px 0;
            font-family: 'Jost', sans-serif;
        }

        /* Header & Breadcrumb */
        .cart-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .cart-header h1 {
            font-size: 2.2rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 10px;
            color: var(--primary-color);
        }

        .cart-header .breadcrumb {
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 2px;
            color: #888;
            text-transform: uppercase;
        }

        .cart-header .breadcrumb a {
            color: #888;
            text-decoration: none;
            transition: color 0.3s;
        }

        .cart-header .breadcrumb a:hover {
            color: var(--accent-color);
        }

        .checkout-progress {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
            gap: 15px;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #9ca3af;
            font-size: 14px;
            font-weight: 500;
        }

        .step.active {
            color: var(--accent-color);
            font-weight: 700;
        }

        .step-number {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            border: 2px solid currentColor;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }

        .step.active .step-number {
            background: var(--accent-color);
            color: white;
            border-color: var(--accent-color);
        }

        .step-line {
            width: 50px;
            height: 2px;
            background: var(--border-color);
        }

        /* Main */
        .checkout-grid {
            max-width: 1050px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.6fr 1fr;
            gap: 25px;
            padding: 0 15px;
        }

        .checkout-card {
            background: white;
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            margin-bottom: 20px;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 800;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-title i {
            font-size: 1.5rem;
            color: var(--accent-color);
        }

        /* Form nhập thông tin khách hàng */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 12px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #4b5563;
        }

        .required {
            color: #ef4444;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            transition: all 0.3s;
        }

        .form-input {
            width: 100%;
            padding: 11px 15px 11px 40px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
            background: #fff;
            outline: none;
        }

        .form-input:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.03);
        }

        .form-input:focus+i {
            color: var(--accent-color);
        }

        textarea.form-input {
            min-height: 80px;
            padding-left: 15px;
        }

        /* Form thanh toán */
        .payment-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .payment-bar {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 10px;
            padding: 15px 10px;
            border: 1.5px solid var(--border-color);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #fff;
            position: relative;
        }

        .payment-bar:hover {
            border-color: #999;
            background: #fafafa;
        }

        .payment-bar.active {
            border-color: var(--accent-color);
            background: #fdfdfd;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .radio-circle {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 1.5px solid var(--border-color);
        }

        .payment-bar.active .radio-circle {
            border-color: var(--accent-color);
            background: var(--accent-color);
        }

        .payment-bar.active .radio-circle::after {
            content: '\2713';
            color: white;
            font-size: 10px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .payment-info h6 {
            font-size: 13px;
            font-weight: 700;
            margin: 0 0 2px;
            color: var(--primary-color);
            text-transform: none;
        }

        .payment-info p {
            font-size: 11px;
            color: #888;
            margin: 0;
            line-height: 1.2;
        }

        .payment-bar i.method-icon {
            font-size: 24px;
            color: #9ca3af;
            transition: all 0.3s;
        }

        .payment-bar.active i.method-icon {
            color: var(--accent-color);
        }

        /* Đơn hàng */
        .summary-card {
            position: sticky;
            top: 30px;
            border-top: 5px solid var(--accent-color);
        }

        .summary-items {
            max-height: 380px;
            overflow-y: auto;
            margin-bottom: 25px;
            padding-right: 10px;
        }

        /* thanh cuộn */
        .summary-items::-webkit-scrollbar {
            width: 4px;
        }

        .summary-items::-webkit-scrollbar-thumb {
            background: #ddd;
            border-radius: 10px;
        }

        .summary-item {
            display: flex;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f9f9f9;
        }

        .item-image {
            width: 55px;
            height: 55px;
            border-radius: 10px;
            object-fit: cover;
            background: #f3f4f6;
            border: 1px solid #eee;
        }

        .item-name {
            font-size: 13.5px;
            font-weight: 700;
            margin: 0 0 3px;
            color: #111;
            line-height: 1.4;
        }

        .item-meta {
            font-size: 12.5px;
            color: #6b7280;
        }

        .item-price {
            font-weight: 800;
            color: var(--accent-color);
            font-size: 15px;
            margin-top: 5px;
        }

        .summary-totals {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px dashed #ddd;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14.5px;
            color: #4b5563;
        }

        .total-row.grand-total {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            color: var(--accent-color);
            font-weight: 900;
            font-size: 20px;
        }

        /* QR */
        .qr-section {
            display: none;
            margin-top: 30px;
            padding: 30px;
            background: #fdfdfd;
            border: 2px dashed #ddd;
            border-radius: 20px;
            text-align: center;
        }

        .qr-section.active {
            display: block;
            animation: slideDown 0.4s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Nút thanh toán */
        .btn-submit-main {
            width: 100%;
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit-main:hover {
            background: #333;
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        .btn-submit-main:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            box-shadow: none;
        }

        /* Thông báo thành công */
        .success-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            backdrop-filter: blur(5px);
        }

        .success-overlay.active {
            display: flex;
        }

        .success-box {
            background: white;
            padding: 50px 40px;
            text-align: center;
            max-width: 480px;
            animation: scaleIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .trust-badges {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            opacity: 0.5;
        }

        .badge-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            color: #4b5563;
        }

        .badge-item i {
            font-size: 20px;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }

            .summary-card {
                position: static;
            }

            .checkout-progress {
                display: none;
            }
        }
    </style>

    <div class="checkout-wrapper">
        <div class="cart-header">
            <h1>Thanh toán</h1>
            <div class="breadcrumb">
                <a href="{{ route('home') }}">TRANG CHỦ</a> / 
                <a href="{{ route('cart') }}">GIỎ HÀNG</a> / THANH TOÁN
            </div>
        </div>

        <!-- Progress Indicator -->
        <div class="checkout-progress">
            <div class="step">
                <span class="step-number"><i class='bx bx-cart'></i></span>
                Giỏ hàng
            </div>
            <div class="step-line"></div>
            <div class="step active">
                <span class="step-number">2</span>
                Thanh toán
            </div>
            <div class="step-line"></div>
            <div class="step">
                <span class="step-number">3</span>
                Hoàn tất
            </div>
        </div>

        <div class="checkout-grid">
            <!-- Area Form -->
            <div class="checkout-main">
                <form id="checkout-form">
                    @csrf

                    <!-- Unified Shipping Info -->
                    <div class="checkout-card">
                        <h3 class="card-title"><i class='bx bxs-truck'></i> Thông tin nhận hàng</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Tên khách hàng <span class="required">*</span></label>
                                <div class="input-wrapper">
                                    <input type="text" class="form-input" name="name"
                                        value="{{ auth()->user()->name ?? '' }}" placeholder="VD: Nguyễn Văn A" required>
                                    <i class='bx bx-user'></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email <span class="required">*</span></label>
                                <div class="input-wrapper">
                                    <input type="email" class="form-input" name="email"
                                        value="{{ auth()->user()->email ?? '' }}" placeholder="email@example.com" required>
                                    <i class='bx bx-envelope'></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Số điện thoại <span class="required">*</span></label>
                                <div class="input-wrapper">
                                    <input type="tel" class="form-input" name="phone"
                                        value="{{ auth()->user()->phone ?? '' }}" placeholder="0123 456 789" required>
                                    <i class='bx bx-phone'></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Địa chỉ giao hàng <span class="required">*</span></label>
                                <div class="input-wrapper">
                                    <input type="text" class="form-input" name="address"
                                        value="{{ auth()->user()->address ?? '' }}" placeholder="Số nhà, tên đường..."
                                        required>
                                    <i class='bx bx-map'></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Lời nhắn / Ghi chú giao hàng</label>
                            <textarea class="form-input" name="note" placeholder="VD: Giao giờ hành chính, gọi trước khi đến..."></textarea>
                        </div>

                        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px dashed #eee;">
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="accept_terms" required checked
                                    style="width:17px; height:17px; cursor:pointer">
                                <span class="text-muted" style="font-size: 12px;">Tôi đồng ý với điều khoản & chính sách của
                                    Luma Shoes. <span class="required">*</span></span>
                            </label>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="checkout-card">
                        <h3 class="card-title"><i class='bx bxs-credit-card'></i> Phương thức thanh toán</h3>

                        <div class="payment-options">
                            <label class="payment-bar active" data-method="COD">
                                <div class="radio-circle"></div>
                                <i class='bx bx-money method-icon'></i>
                                <div class="payment-info">
                                    <h6>Tiền mặt (COD)</h6>
                                    <p>Thanh toán khi nhận hàng</p>
                                </div>
                                <input type="radio" name="payment_method" value="COD" checked style="display:none">
                            </label>

                            <label class="payment-bar" data-method="QR">
                                <div class="radio-circle"></div>
                                <i class='bx bx-qr-scan method-icon'></i>
                                <div class="payment-info">
                                    <h6>Chuyển khoản QR</h6>
                                    <p>Quét mã ngân hàng 24/7</p>
                                </div>
                                <input type="radio" name="payment_method" value="QR" style="display:none">
                            </label>
                        </div>

                        <!-- QR Display -->
                        <div class="qr-section" id="qr-section">
                            <h5 class="mb-4" style="font-weight:700">Quét mã QR để hoàn tất thanh toán</h5>

                            <div class="qr-container-box mb-4">
                                <div id="qr-loading" style="padding: 30px;">
                                    <div class="spinner-border text-dark" role="status"></div>
                                    <p class="mt-2 text-muted small">Đang khởi tạo mã QR...</p>
                                </div>
                                <!-- qrcodejs xuất hiện -->
                                <div id="qr-canvas-holder"
                                    style="display:flex; justify-content:center; margin: 10px auto"></div>
                                <img id="qr-img" src="" alt="QR" style="display:none">
                                {{-- <p id="qr-info" class="mt-3 text-muted small">Đang tải thông tin chuyển khoản...</p> --}}
                            </div>

                            <div class="p-3 border rounded-3 bg-light">
                                <label for="transaction-code" class="d-block mb-2 fw-bold text-dark">Nội dung chuyển
                                    khoản</label>
                                <input type="text" id="transaction-code" name="transaction_code" placeholder="..."
                                    class="form-input text-center text-uppercase fw-bold"
                                    style="padding-left:15px; font-size: 14px; border-style: dashed; color: #d32f2f; letter-spacing: 1px; margin: 10px;">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Sidebar Summary -->
            <div class="checkout-sidebar">
                <div class="checkout-card summary-card">
                    <h3 class="card-title"><i class='bx bxs-shopping-bag'></i> Tóm tắt đơn hàng</h3>

                    <div class="summary-items">
                        @foreach ($cartItems as $item)
                            <div class="summary-item">
                                <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}" class="item-image">
                                <div class="item-info">
                                    <h4 class="item-name">{{ $item->product_name }}</h4>
                                    <div class="item-meta">Size: {{ $item->size }} | SL: {{ $item->quantity }}</div>
                                    <div class="item-price">
                                        {{ \App\Helpers\Helpers::format(\App\Helpers\Helpers::parse($item->price)) }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="summary-totals">
                        <div class="total-row">
                            <span>Tạm tính</span>
                            <span class="fw-bold">{{ \App\Helpers\Helpers::format($total) }}</span>
                        </div>
                        <div class="total-row">
                            <span>Phí vận chuyển</span>
                            <span class="text-success fw-bold">Miễn phí</span>
                        </div>
                        <div class="total-row grand-total">
                            <span>TỔNG CỘNG</span>
                            <span>{{ \App\Helpers\Helpers::format($total) }}</span>
                        </div>
                    </div>

                    <button type="button" class="btn-submit-main" id="submit-order">
                        HOÀN TẤT ĐẶT HÀNG <i class='bx bx-right-arrow-alt'></i>
                    </button>

                    <div class="trust-badges">
                        <div class="badge-item">
                            <i class='bx bx-shield-quarter'></i>
                            <span>An toàn</span>
                        </div>
                        <div class="badge-item">
                            <i class='bx bx-refresh'></i>
                            <span>Đổi trả</span>
                        </div>
                        <div class="badge-item">
                            <i class='bx bx-check-double'></i>
                            <span>Chính hãng</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="success-overlay" id="success-modal">
        <div class="success-box">
            <div class="success-icon"
                style="background: #27ae60; width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px;">
                <i class="fa fa-check" style="font-size: 35px; color: white;"></i>
            </div>
            <h3 style="font-weight: 800; font-size: 24px; color: #2c2c2c; margin-bottom: 16px;">Đặt hàng thành công!</h3>
            <p style="color: #666; font-size: 14px; margin-bottom: 30px; line-height: 1.6;">Cảm ơn bạn đã đặt hàng. Chúng
                tôi sẽ liên hệ với bạn trong thời gian sớm nhất.</p>
            <button class="btn-home"
                style="padding: 14px 40px; background: #2c2c2c; color: white; border: none; font-size: 14px; font-weight: 500; cursor: pointer; transition: background 0.3s ease; border-radius: 8px;"
                onclick="window.location.href='{{ route('home') }}'">
                <i class="fa fa-home"></i> Về trang chủ
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentBars = document.querySelectorAll('.payment-bar');
            const qrSection = document.getElementById('qr-section');
            const transactionInput = document.getElementById('transaction-code');
            const submitBtn = document.getElementById('submit-order');
            const form = document.getElementById('checkout-form');

            const generateQRUrl = '/generate-qr';
            const checkoutUrl = '/gio-hang/checkout';

            let qrLoaded = false;
            let qrInterval;

            // Thanh toán
            paymentBars.forEach(bar => {
                bar.addEventListener('click', function() {
                    paymentBars.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    const radio = this.querySelector('input[type="radio"]');
                    if (radio) radio.checked = true;

                    const method = this.dataset.method;
                    if (method === 'QR') {
                        qrSection.classList.add('active');
                        if (!qrLoaded) loadQR();
                    } else {
                        qrSection.classList.remove('active');
                        if (qrInterval) clearInterval(qrInterval);
                    }
                });
            });

            // Mã thanh toán
            function loadQR() {
                // const qrInfo = document.getElementById('qr-info');
                const qrLoading = document.getElementById('qr-loading');
                const canvasHolder = document.getElementById('qr-canvas-holder');

                qrLoaded = true;
                qrLoading.style.display = 'block';
                canvasHolder.innerHTML = '';

                fetch(generateQRUrl)
                    .then(res => res.json())
                    .then(data => {
                        qrLoading.style.display = 'none';
                        if (data.success) {
                            // Show VietQR
                            const img = document.createElement('img');
                            img.src = data.qr_image;
                            img.style.cssText =
                                'max-width: 280px; width: 100%; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: 1px solid #eee;';
                            canvasHolder.appendChild(img);

                            // thông tin ngân hàng
                            // qrInfo.innerHTML = `
                        //     <div style="background:#f8f9fa; padding:15px; border-radius:10px; border:1px solid #eee; text-align:left; max-width:320px; margin:10px auto">
                        //         <strong><i class='bx bxs-bank'></i> ${data.bank_info.bank}</strong><br>
                        //         STK: <strong style="color:var(--accent-color)">${data.bank_info.account}</strong><br>
                        //         Tên: <strong>${data.bank_info.account_name}</strong>
                        //     </div>
                        // `;

                            if (transactionInput) {
                                transactionInput.value = data.transfer_code || data.qr_code;
                                // Lưu mã QR để kiểm tra
                                sessionStorage.setItem('qr_transfer_code', data.transfer_code);
                                sessionStorage.setItem('qr_code', data.qr_code);
                            }

                            if (qrInterval) clearInterval(qrInterval);
                            qrInterval = setInterval(loadQR, 300000); // 5 phút
                        }
                    });
            }

            // Hoàn tất đặt hàng
            if (submitBtn && form) {
                submitBtn.addEventListener('click', async function(e) {
                    e.preventDefault();

                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }

                    // qr kiểm tra mã đơn hàng
                    const method = document.querySelector('input[name="payment_method"]:checked')
                        ?.value;
                    if (method === 'QR') {
                        const inputVal = transactionInput.value.trim().toUpperCase();
                        const storedTCode = sessionStorage.getItem('qr_transfer_code');
                        const storedQCode = sessionStorage.getItem('qr_code');

                        const isValid = (inputVal === storedTCode) || (inputVal === storedQCode) || (
                            inputVal === 'DH' + storedQCode);

                        if (!isValid) {
                            alert(
                                'Mã giao dịch (Nội dung chuyển khoản) không hợp lệ.\nVui lòng nhập đúng nội dung ghi trên mã QR!'
                            );
                            transactionInput.focus();
                            return;
                        }
                    }

                    submitBtn.disabled = true;
                    submitBtn.innerHTML = "<i class='bx bx-loader-alt bx-spin'></i> ĐANG XỬ LÝ...";

                    try {
                        const formData = new FormData(form);
                        const res = await fetch(checkoutUrl, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const data = await res.json();
                        if (data.success) {
                            document.getElementById('success-modal').classList.add('active');
                        } else {
                            alert('Lỗi: ' + (data.message || 'Có sự cố xảy ra.'));
                        }
                    } catch (err) {
                        alert('Lỗi kết nối máy chủ!');
                    } finally {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = "HOÀN TẤT ĐẶT HÀNG <i class='bx bx-right-arrow-alt'></i>";
                    }
                });
            }
        });
    </script>
@endsection

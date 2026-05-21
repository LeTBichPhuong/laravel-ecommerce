<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Giỏ hàng - Luma Shoes</title>
    <link rel="stylesheet" href="{{ asset('Home.css') }}">
    <link rel="website icon" href="{{ asset('img/Logo.png') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('Cart.css') }}">
</head>

<body>
    <!-- Header -->
    <header>
        <nav class="nav-menu">
            <ul class="menu">
                <li><a href="{{ route('home') }}">Trang chủ</a></li>
                <li><a href="{{ route('about') }}">Giới thiệu</a></li>
                <li><a href="{{ route('brand') }}">Thương hiệu</a></li>
                <li class="logo"><a href="{{ route('home') }}"><span class="logo-text">LUMA SHOES</span></a></li>
                <li class="nav-item">
                    <a href="{{ route('products.list') }}">Sản phẩm</a>
                    <ul class="submenu">
                        <li><a href="{{ route('products.gender', ['gender' => 'Men']) }}">Giày thể thao nam</a></li>
                        <li><a href="{{ route('products.gender', ['gender' => 'Women']) }}">Giày thể thao nữ</a></li>
                        <li><a href="{{ route('products.gender', ['gender' => 'Unisex']) }}">Mẫu Unisex</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('blog') }}">Blog</a></li>
                <!-- Thanh tìm kiếm ẩn -->
                <li class="search-container" style="position:relative;">
                    <a href="#" id="searchIcon"><i class="fa fa-search"></i></a>
                    <form id="searchForm" class="search-form" action="#" method="get" onsubmit="return false;">
                        <div class="search-box">
                            <input type="text" name="keyword" placeholder="Tìm kiếm sản phẩm hoặc thương hiệu..."
                                autocomplete="off" />
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                    <div id="searchResults" class="search-results" aria-live="polite"></div>
                </li>
                <!-- Tài khoản -->
                <li>
                    @if (auth()->check())
                        <a href="{{ route('account') }}"><i class="fa fa-user"></i></a>
                    @else
                        <a href="{{ route('login') }}"><i class="fa fa-user"></i></a>
                    @endif
                </li>
                <li><a href="{{ route('cart') }}"><i class="fa fa-shopping-cart"></i></a></li>
            </ul>
        </nav>
    </header>


    <!-- Main Content -->
    <main class="studio-container">
        <div class="cart-header">
            <h1>Giỏ Hàng</h1>
            <div class="breadcrumb">
                <a href="{{ route('home') }}">TRANG CHỦ</a> / GIỎ HÀNG
            </div>
        </div>

        <!-- Progress Indicator -->
        <div class="checkout-progress">
            <div class="step active">
                <span class="step-number"><i class='bx bx-cart'></i></span>
                Giỏ hàng
            </div>
            <div class="step-line"></div>
            <div class="step">
                <span class="step-number">2</span>
                Thanh toán
            </div>
            <div class="step-line"></div>
            <div class="step">
                <span class="step-number">3</span>
                Hoàn tất
            </div>
        </div>

        @if (isset($cartItems) && $cartItems->count() > 0)
            <div class="cart-layout">
                <!-- Danh sách sản phẩm -->
                <div class="cart-table-wrapper">
                    <table class="table-premium">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Đơn giá</th>
                                <th>Số lượng</th>
                                <th>Tổng</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cartItems as $item)
                                <tr data-id="{{ $item->id }}">
                                    <td>
                                        <div class="cart-product-cell">
                                            <div class="cart-product-img">
                                                <img src="{{ $item->product_image ?? asset('img/placeholder-product.png') }}"
                                                    alt="{{ $item->product_name }}">
                                            </div>
                                            <div class="cart-product-details">
                                                <h3>{{ $item->product_name }}</h3>
                                                <p>Size: {{ $item->size ?? 'Mặc định' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="price" data-price="{{ $item->price_numeric }}"
                                        data-price-raw="{{ $item->price }}">
                                        {{ \App\Helpers\helpers::format($item->price_numeric) }}
                                    </td>
                                    <td>
                                        <!-- Note: Using standard class "quantity" for Cart.js compatibility -->
                                        <div class="quantity-wrapper">
                                            <button type="button" class="qty-btn minus">-</button>
                                            <input type="number" value="{{ $item->quantity }}" min="1"
                                                class="quantity" data-id="{{ $item->id }}" readonly>
                                            <button type="button" class="qty-btn plus">+</button>
                                        </div>
                                    </td>
                                    <td class="price-cell subtotal">
                                        {{ \App\Helpers\helpers::format($item->subtotal) }}
                                    </td>
                                    <td>
                                        <!-- Note: Using standard class "remove-item" for Cart.js compatibility -->
                                        <button type="button" class="remove-btn-premium remove-item"
                                            data-id="{{ $item->id }}" title="Xóa sản phẩm">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Tóm tắt đơn hàng -->
                <aside class="cart-summary">
                    <h2 class="summary-title">Tóm tắt đơn hàng</h2>

                    <div class="summary-row">
                        <span>Tạm tính</span>
                        <span id="summary-subtotal">{{ \App\Helpers\helpers::format($total) }}</span>
                    </div>

                    <div class="summary-row">
                        <span>Phí giao hàng</span>
                        <span>Miễn phí</span>
                    </div>

                    <div class="summary-total">
                        <span>Tổng cộng</span>
                        <span id="summary-total-price">{{ \App\Helpers\helpers::format($total) }}</span>
                    </div>

                    <button type="button" class="btn-checkout-premium" id="checkout-btn"
                        onclick="window.location.href='{{ route('checkout') }}'">
                        TIẾN HÀNH THANH TOÁN
                    </button>
                </aside>
            </div>
        @else
            <div class="empty-cart-premium">
                <i class="bx bx-shopping-bag"></i>
                <h3>Giỏ hàng của bạn đang trống</h3>
                <p>Khám phá các sản phẩm mới nhất của chúng tôi và tìm ngay cho mình phong cách phù hợp.</p>
                <a href="{{ route('products.list') }}" class="btn-shop-premium">Khám Phá Cửa Hàng</a>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-top">
            <div class="title-sub">
                <h2>Đăng ký thành viên để nhận khuyến mãi</h2>
                <p>Theo dõi chúng tôi để nhận nhiều ưu đãi</p>
            </div>
            <div class="subscribe">
                <input type="email" placeholder="Nhập email, SĐT của bạn">
                <button class="btn-sub"><i class='bx bxs-navigation'></i></button>
            </div>
        </div>
        <hr class="section-divider">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Kết nối với Luma Shoes</h3>
                <div class="social-icons">
                    <i class="fab fa-facebook-f"></i>
                    <i class="fab fa-x-twitter"></i>
                    <i class="fab fa-pinterest"></i>
                    <i class="fab fa-instagram"></i>
                </div>
                <p>TỔNG ĐÀI TƯ VẤN MIỄN PHÍ</p>
                <p>Chi nhánh HÀ NỘI<br>0386759355</p>
            </div>

            <div class="footer-section">
                <h3>Chính sách bán hàng</h3>
                <ul>
                    <li>Chính sách và quy định chung</li>
                    <li>Chính sách bảo mật</li>
                    <li>Vận chuyển và giao nhận</li>
                    <li>Mua hàng và thanh toán</li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Thông tin chung</h3>
                <ul>
                    <li>Giới thiệu</li>
                    <li>Blog</li>
                    <li>Liên hệ</li>
                    <li>Sản phẩm</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© Luma Shoes 2025 | all rights reserved</p>
        </div>
    </footer>

    <script src="{{ asset('JS/Product.js') }}"></script>
    <script src="{{ asset('JS/script.js') }}"></script>
    <script src="{{ asset('JS/Cart.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof showToast === 'function') {
                    showToast("{{ session('success') }}", "success");
                }
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof showToast === 'function') {
                    showToast("{{ session('error') }}", "error");
                }
            });
        </script>
    @endif

    @if (auth()->check() && auth()->user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}" class="admin-floater" title="Vào trang Admin">
            <i class="bx bx-shield-alt-2"></i>
            <span>Admin</span>
        </a>
        <style>
            .admin-floater {
                position: fixed;
                bottom: 100px;
                right: 24px;
                background: #111;
                color: #fff;
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 12px 18px;
                font-size: .78rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 1.5px;
                text-decoration: none;
                z-index: 9999;
                border: 2px solid transparent;
                box-shadow: 0 8px 24px rgba(0, 0, 0, .25);
                transition: all .3s ease;
            }

            .admin-floater:hover {
                background: #fff;
                color: #111;
                border-color: #111;
            }

            .admin-floater i {
                font-size: 1.1rem;
            }
        </style>
    @endif
</body>

</html>

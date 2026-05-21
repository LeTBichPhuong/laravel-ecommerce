<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Luma Shoes')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/Logo.png') }}">
    <link rel="stylesheet" href="{{ asset('Home.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @yield('css')
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
    @section('main_content')
        <section class="hero">
            <div class="overlay">
                <a href="{{ route('products.list') }}">
                    <img src="{{ asset('img/banner_premium_dark.png') }}" alt="Hero Banner">
                    <div class="hero-content">
                        <h1><i>NIKE</i></h1>
                        <p>AIR FORCE 1 | JORDAN 1 | BLAZER | DUNK | COURT</p>
                        <span class="btn-shop-now">XEM TẠI ĐÂY</span>
                    </div>
                </a>
            </div>
        </section>
        <!-- End header -->

        <!-- Main -->
        <!-- Thương hiệu -->
        <section class="brands">
            <h2>Thương Hiệu Nổi Bật</h2>
            <div class="brand-grid"></div>
        </section>
        <!-- End thương hiệu -->
        <hr class="section-divider">
        <!-- Sản phẩm -->
        <section class="main-section layout-grid">
            <div class="section-header-flex">
                <h2 class="title-left">GIÀY SNEAKER</h2>
                <div class="tabs-right">
                    <span class="tab active" data-tab="Men" onclick="showTab('Men', event)">Giày Nam</span>
                    <span class="tab" data-tab="Women" onclick="showTab('Women', event)">Giày Nữ</span>
                    <span class="tab" data-tab="Unisex" onclick="showTab('Unisex', event)">Classics / Unisex</span>
                </div>
            </div>
            <div class="slider-container grid-container"></div>
        </section>
        <!-- End sản phẩm -->
        <hr class="section-divider">
        <!-- Giới thiệu -->
        <section class="why-choose">
            <h2 class="titles">CHẤT LƯỢNG & TRẢI NGHIỆM</h2>
            <div class="benefits">
                <div class="benefit">
                    <i class="fas fa-credit-card"></i>
                    <div class="benefit-content">
                        <h3>Khách Hàng Thân Thiết</h3>
                        <p>Tích điểm nâng hạng, nhận ưu đãi độc quyền 5% đến 15% cho mỗi đơn hàng tiếp theo.</p>
                    </div>
                </div>
                <div class="benefit">
                    <i class="fas fa-shipping-fast"></i>
                    <div class="benefit-content">
                        <h3>Giao Hàng Siêu Tốc</h3>
                        <p>Miễn phí vận chuyển toàn quốc cho các hóa đơn mua giày. Trải nghiệm mở hộp nhanh chóng.</p>
                    </div>
                </div>
                <div class="benefit">
                    <i class="fas fa-award"></i>
                    <div class="benefit-content">
                        <h3>Cam Kết Chính Hãng</h3>
                        <p>100% Sneaker chính hãng (Authentic). Phát hiện Fake đền bù gấp 10 lần giá trị.</p>
                    </div>
                </div>
            </div>
        </section>
        <hr class="section-divider">
        <!-- Tin tức (Blog) -->
        <section class="news-section">
            <h2 class="section-title">TIN TỨC</h2>
            <div class="news-grid">
                <div class="news-card">
                    <div class="news-img">
                        <img src="{{ asset('img/Sneaker-nam.png') }}" alt="Tips Mix Đồ">
                    </div>
                    <div class="news-content">
                        <h3>Tips Mix Đồ Ấn Tượng Cùng Sneaker Cổ Cao Cực Chất Cho Bạn</h3>
                        <p>Sneaker cổ cao là một trong những item không thể thiếu trong tủ giày của bất cứ tín đồ thời trang
                            nào...</p>
                        <div class="news-footer">
                            <span class="news-date"><i class='bx bx-calendar'></i> 27/09/2022</span>
                            <a href="#" class="news-more">Xem thêm »</a>
                        </div>
                    </div>
                </div>
                <div class="news-card">
                    <div class="news-img">
                        <img src="{{ asset('img/Sneaker-unisex.png') }}" alt="Cách Đo Chân">
                    </div>
                    <div class="news-content">
                        <h3>Cách Đo Chân Để Chọn Size Giày Cho Chuẩn</h3>
                        <p>Có bao giờ bạn băn khoăn về việc mua giày online nhưng không chắc chắn đôi chân của mình ở size
                            nào?</p>
                        <div class="news-footer">
                            <span class="news-date"><i class='bx bx-calendar'></i> 27/09/2022</span>
                            <a href="#" class="news-more">Xem thêm »</a>
                        </div>
                    </div>
                </div>
                <div class="news-card">
                    <div class="news-img">
                        <img src="{{ asset('img/Sneaker-nu.png') }}" alt="Nét Nữ Tính">
                    </div>
                    <div class="news-content">
                        <h3>Trọn Vẹn Nét Nữ Tính Với Sneaker</h3>
                        <p>Nhiều bạn quan niệm rằng những đôi sneaker khỏe khoắn có thể làm mất đi nét nữ tính vốn có của
                            mình...</p>
                        <div class="news-footer">
                            <span class="news-date"><i class='bx bx-calendar'></i> 27/09/2022</span>
                            <a href="#" class="news-more">Xem thêm »</a>
                        </div>
                    </div>
                </div>
                <div class="news-card">
                    <div class="news-img">
                        <img src="{{ asset('img/Banner.PNG') }}" alt="Spa Giày">
                    </div>
                    <div class="news-content">
                        <h3>Những Lý Do Bạn Nên Đưa Giày Đi Spa</h3>
                        <p>Tại Việt Nam việc đưa giày đi spa không còn là việc quá xa lạ. Spa giày giúp đôi giày của bạn
                            luôn mới...</p>
                        <div class="news-footer">
                            <span class="news-date"><i class='bx bx-calendar'></i> 27/09/2022</span>
                            <a href="#" class="news-more">Xem thêm »</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End tin tức -->

        <!-- Chatbot Widget -->
        <div id="chatbot-container">
            <button id="chatbot-toggle-btn" onclick="toggleChatbot()">
                <i class='bx bx-message-alt-dots'></i>
            </button>
            <div id="chatbot-box">
                <div id="chatbot-header">
                    <span><i class='bx bx-message-alt-dots'></i> Luma Shoes Bot</span>
                    <button id="chatbot-close" onclick="toggleChatbot()">×</button>
                </div>
                <div id="chatbot-messages"></div>
                <div id="chatbot-input-area">
                    <input type="text" id="chatbot-input" placeholder="Nhập tin nhắn..."
                        onkeydown="handleKey(event)" />
                    <button onclick="sendMessage()">Gửi</button>
                </div>
            </div>
        </div>
    @show
    <!-- End main -->

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
    <!-- End footer -->

    <script src="{{ asset('JS/script.js') }}"></script>
    <script src="{{ asset('JS/Product.js') }}"></script>
    @include('Layouts.QuickView')
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

@extends('Home')

@section('title', 'Về chúng tôi - Luma Shoes')

@section('css')
    <style>
        :root {
            --premium-black: #111;
            --premium-white: #fff;
            --premium-grey: #f4f4f4;
            --premium-silver: #e0e0e0;
            --accent-color: #b00000;
            --transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        /* Hero Section - Khớp với trang Home */
        .about-hero {
            height: 450px;
            max-width: 1100px;
            margin: 80px auto 40px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
            overflow: hidden;
        }

        .hero-image {
            position: absolute;
            inset: 0;
            background: url('https://images.unsplash.com/photo-1556906781-9a412961c28c?auto=format&fit=crop&q=80&w=2000') center/cover no-repeat;
            opacity: 0.6;
            filter: grayscale(100%);
        }

        .hero-content-about {
            position: relative;
            z-index: 2;
            text-align: center;
            color: #fff;
            text-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }

        .hero-title-about {
            font-size: 80px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -2px;
            margin: 0;
            line-height: 1;
            font-style: italic;
        }

        .hero-subtitle-about {
            font-size: 16px;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 500;
            margin-top: 10px;
            opacity: 1;
        }

        /* Common Layout */
        .section-container-about {
            max-width: 1080px;
            margin: 0 auto;
            padding: 40px 24px;
        }

        .section-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-header h2 {
            font-size: 1.6rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: inline-block;
            position: relative;
            padding-bottom: 15px;
        }

        .section-header h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: var(--premium-black);
        }

        /* Story Section */
        .story-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .story-image {
            position: relative;
        }

        .story-image img {
            width: 100%;
            border-radius: 4px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.12);
        }

        .story-image::before {
            content: '';
            position: absolute;
            top: -20px;
            left: -20px;
            width: 100px;
            height: 100px;
            border-top: 5px solid var(--premium-black);
            border-left: 5px solid var(--premium-black);
            z-index: -1;
        }

        .story-text p {
            font-size: 0.9rem;
            line-height: 1.7;
            color: #444;
            margin-bottom: 15px;
        }

        .story-text .quote {
            font-style: italic;
            font-size: 1.05rem;
            border-left: 3px solid var(--accent-color);
            padding-left: 15px;
            margin: 20px 0;
            color: #222;
            font-weight: 500;
        }

        /* Mission & Vision */
        .mission-vision {
            background: var(--premium-grey);
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .mv-item {
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .mv-item:first-child {
            background: #fff;
            text-align: right;
            align-items: flex-end;
        }

        .mv-item h3 {
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .mv-item p {
            font-size: 0.85rem;
            color: #666;
            max-width: 400px;
            line-height: 1.6;
        }

        /* Stats Section - Màu Đỏ nổi bật nhất */
        .stats-section {
            padding: 60px 0;
            background: var(--accent-color);
            color: #fff;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-item h3 {
            font-size: 2.2rem;
            font-weight: 950;
            margin-bottom: 5px;
            color: var(--premium-white);
        }

        .stat-item span {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: rgba(255, 255, 255, 0.5);
        }

        /* Values Section */
        .values-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .value-card {
            padding: 30px 20px;
            border: 1px solid var(--premium-silver);
            text-align: center;
            transition: var(--transition);
        }

        .value-card:hover {
            background: var(--premium-black);
            color: #fff;
            transform: translateY(-10px);
            border-color: var(--premium-black);
        }

        .value-icon {
            font-size: 2.2rem;
            margin-bottom: 15px;
            display: block;
        }

        .value-card:hover .value-icon {
            color: #fff;
        }

        .value-card h3 {
            font-size: 1.1rem;
            font-weight: 800;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .value-card p {
            font-size: 0.85rem;
            line-height: 1.5;
            opacity: 0.8;
        }

        /* Timeline Section */
        .timeline-section {
            padding: 100px 0;
            background: #fff;
        }

        .timeline {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
        }

        .timeline::after {
            content: '';
            position: absolute;
            width: 2px;
            background: var(--premium-silver);
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -1px;
        }

        .t-container {
            padding: 10px 40px;
            position: relative;
            background: inherit;
            width: 50%;
        }

        .t-container::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            right: -8px;
            background-color: var(--premium-white);
            border: 3px solid var(--premium-black);
            top: 15px;
            border-radius: 50%;
            z-index: 1;
        }

        .left-t {
            left: 0;
        }

        .right-t {
            left: 50%;
        }

        .right-t::after {
            left: -8px;
        }

        .t-content {
            padding: 15px 20px;
            background-color: var(--premium-grey);
            position: relative;
            border-radius: 4px;
        }

        .t-content h4 {
            font-weight: 800;
            font-size: 0.95rem;
            margin-bottom: 5px;
        }

        .t-content .year {
            color: var(--accent-color);
            font-weight: 900;
            font-size: 0.9rem;
            margin-bottom: 5px;
            display: block;
        }

        .t-content p {
            font-size: 0.8rem;
            line-height: 1.5;
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 860px) {

            .story-grid,
            .mission-vision,
            .values-grid,
            .stats-grid {
                grid-template-columns: 1fr !important;
                text-align: center !important;
            }

            .mv-item {
                align-items: center !important;
                padding: 40px 20px !important;
            }

            .timeline::after {
                left: 31px;
            }

            .t-container {
                width: 100%;
                padding-left: 70px;
                padding-right: 25px;
            }

            .t-container::after {
                left: 23px;
            }

            .right-t {
                left: 0%;
            }
        }
    </style>
@endsection

@section('main_content')
    <!-- Hero Section -->
    <section class="about-hero">
        <div class="hero-image"></div>
        <div class="hero-content-about">
            <h1 class="hero-title-about">Luma Shoes</h1>
            <p class="hero-subtitle-about">Định Nghĩa Lại Phong Cách Sống</p>
        </div>
    </section>

    <!-- Our Story -->
    <section class="section-container-about">
        <div class="story-grid">
            <div class="story-image">
                <img src="https://images.unsplash.com/photo-1491553895911-0055eca6402d?auto=format&fit=crop&q=80&w=1000"
                    alt="Luma Shoes Heritage">
            </div>
            <div class="story-text">
                <div class="section-header" style="text-align: left; margin-bottom: 30px;">
                    <h2 style="padding-bottom: 10px;">Di Sản Của Chúng Tôi</h2>
                </div>
                <p>Khởi nguồn từ niềm đam mê mãnh liệt với những chuyển động của văn hóa đường phố, Luma Shoes ra đời không
                    chỉ là một cửa hàng giày, mà là một điểm đến của những tâm hồn đồng điệu.</p>
                <div class="quote">
                    "Chúng tôi không bán giày, chúng tôi bán sự tự tin trong mỗi bước đi của bạn."
                </div>
                <p>Tại Luma Shoes, mỗi đôi giày đều mang trong mình một câu chuyện riêng về sự sáng tạo, bền bỉ và cái tôi
                    cá nhân. Chúng tôi khao khát được đồng hành cùng bạn trên mọi hành trình chinh phục những đỉnh cao mới.
                </p>
            </div>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="mission-vision">
        <div class="mv-item">
            <h3>Sứ Mệnh</h3>
            <p>Mang văn hóa Sneaker chính hãng và chất lượng cao nhất đến gần hơn với mọi người Việt Nam, tạo dựng một cộng
                đồng văn minh và đầy đam mê.</p>
        </div>
        <div class="mv-item" style="background: var(--premium-black); color: #fff;">
            <h3>Tầm Nhìn</h3>
            <p style="color: rgba(255,255,255,0.7);">Trở thành biểu tượng hàng đầu trong lĩnh vực thời trang đường phố tại
                khu vực, là sự lựa chọn số 1 cho mọi tín đồ yêu giày chính hãng.</p>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="section-container-about">
            <div class="stats-grid">
                <div class="stat-item">
                    <h3>25K+</h3>
                    <span>Đôi giày đã bán</span>
                </div>
                <div class="stat-item">
                    <h3>120+</h3>
                    <span>Mẫu giới hạn</span>
                </div>
                <div class="stat-item">
                    <h3>15K+</h3>
                    <span>Thành viên thân thiết</span>
                </div>
                <div class="stat-item">
                    <h3>100%</h3>
                    <span>Cam kết chính hãng</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline -->
    <section class="timeline-section">
        <div class="section-container-about">
            <div class="section-header">
                <h2>Hành Trình Phát Triển</h2>
            </div>
            <div class="timeline">
                <div class="t-container left-t">
                    <div class="t-content">
                        <span class="year">2020</span>
                        <h4>Nền Móng Đầu Tiên</h4>
                        <p>Cửa hàng nhỏ đầu tiên tại Hà Nội được khai trương với 5 thành viên sáng lập.</p>
                    </div>
                </div>
                <div class="t-container right-t">
                    <div class="t-content">
                        <span class="year">2021</span>
                        <h4>Mở Rộng Quy Mô</h4>
                        <p>Trở thành đối tác phân phối ủy quyền của 10 thương hiệu lớn tại Việt Nam.</p>
                    </div>
                </div>
                <div class="t-container left-t">
                    <div class="t-content">
                        <span class="year">2022</span>
                        <h4>Chuyển Đổi Số</h4>
                        <p>Ra mắt hệ thống website hiện đại và ứng dụng mua sắm trên di động.</p>
                    </div>
                </div>
                <div class="t-container right-t">
                    <div class="t-content">
                        <span class="year">2024</span>
                        <h4>Vươn Tầm Thương Hiệu</h4>
                        <p>Đạt mốc 25.000 khách hàng và mở rộng hệ thống chuỗi cửa hàng vật lý.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values -->
    <section class="values-section" style="padding-bottom: 80px; background: #fff;">
        <div class="section-container-about">
            <div class="section-header">
                <h2>Giá Trị Cốt Lõi</h2>
            </div>
            <div class="values-grid">
                <div class="value-card">
                    <i class='bx bx-check-double value-icon' style="color: var(--accent-color);"></i>
                    <h3>Chất Lượng Tuyệt Đối</h3>
                    <p>Mỗi đôi giày đều qua quy trình kiểm tra nghiêm ngặt về nguồn gốc và chất lượng trước khi đến tay bạn.
                    </p>
                </div>
                <div class="value-card">
                    <i class='bx bx-refresh value-icon' style="color: var(--accent-color);"></i>
                    <h3>Dịch Vụ Tận Tâm</h3>
                    <p>Chính sách đổi trả linh hoạt trong 30 ngày và bảo hành keo chỉ trọn đời cho mọi sản phẩm.</p>
                </div>
                <div class="value-card">
                    <i class='bx bx-medal value-icon' style="color: var(--accent-color);"></i>
                    <h3>Trải Nghiệm Premium</h3>
                    <p>Không chỉ là mua sắm, chúng tôi mang đến một không gian văn hóa đầy cảm hứng và đẳng cấp.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="section-container-about" style="padding-top: 0; padding-bottom: 100px;">
        <div
            style="background: var(--premium-black); color: #fff; padding: 60px 40px; text-align: center; border-radius: 15px; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
            <h2
                style="font-weight: 900; text-transform: uppercase; margin-bottom: 20px; letter-spacing: 2px; font-size: 1.5rem;">
                Sẵn sàng để bắt đầu hành trình mới?</h2>
            <p style="margin-bottom: 30px; opacity: 0.7; font-size: 0.9rem;">Hãy cùng Luma Shoes bước tiếp những bước chân
                đầy đam mê và phong cách.</p>
            <a href="{{ route('products.list') }}" class="btn-shop-now"
                style="background: #fff; color: #000; padding: 15px 40px; border-radius: 50px; font-weight: 800; display: inline-block;">Khám
                Phá Bộ Sưu Tập</a>
        </div>
    </section>
@endsection

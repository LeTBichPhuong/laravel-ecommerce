@extends('Home')

@section('title', 'Blog - Luma Shoes Journal')

@section('css')
    <style>
        :root {
            --premium-black: #111;
            --premium-white: #fff;
            --premium-grey: #f4f4f4;
            --premium-grey-dark: #666;
            --accent-color: #d32f2f;
            --transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        /* Tinh chỉnh trang Blog gọn gàng hơn */
        .blog-header {
            padding: 80px 0 40px;
            text-align: center;
            background: linear-gradient(to bottom, #fafafa, #ffffff);
        }

        .journal-tag {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 6px;
            color: var(--accent-color);
            font-weight: 700;
            display: block;
            margin-bottom: 10px;
        }

        .journal-title {
            font-size: 3rem;
            font-weight: 900;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: -1px;
        }

        .journal-subtitle {
            font-size: 1rem;
            color: var(--premium-grey-dark);
            max-width: 600px;
            margin: 10px auto 0;
        }

        .blog-filters {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 30px 0 50px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }

        .filter-btn {
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--premium-grey-dark);
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            background: none;
            border: none;
            padding: 8px 0;
        }

        .filter-btn:hover,
        .filter-btn.active {
            color: var(--premium-black);
        }

        .filter-btn.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 2px;
            background: var(--premium-black);
        }

        .container-blog {
            max-width: 1080px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .featured-card {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 60px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
            min-height: 400px;
        }

        .featured-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.08);
        }

        .featured-image {
            background: url('https://images.unsplash.com/photo-1556906781-9a412961c28c?auto=format&fit=crop&q=80&w=1500') center/cover no-repeat;
            position: relative;
        }

        .featured-image .badge {
            position: absolute;
            top: 25px;
            left: 25px;
            background: var(--premium-black);
            color: #fff;
            padding: 6px 15px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .featured-content {
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #fff;
        }

        .post-meta {
            font-size: 0.8rem;
            color: var(--premium-grey-dark);
            margin-bottom: 15px;
            display: flex;
            gap: 15px;
        }

        .featured-content h2 {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 15px;
        }

        .featured-content p {
            font-size: 0.95rem;
            color: #555;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .read-more-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-size: 0.8rem;
            color: var(--premium-black);
            transition: var(--transition);
            text-decoration: none;
        }

        .read-more-btn i {
            font-size: 1.1rem;
            transition: transform 0.3s ease;
        }

        .read-more-btn:hover i {
            transform: translateX(5px);
        }

        .blog-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-bottom: 80px;
        }

        .blog-card {
            background: transparent;
            transition: var(--transition);
        }

        .blog-media {
            width: 100%;
            padding-top: 70%;
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 18px;
        }

        .blog-media img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .blog-card:hover .blog-media img {
            transform: scale(1.08);
        }

        .blog-card .badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(255, 255, 255, 0.9);
            color: var(--premium-black);
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            backdrop-filter: blur(5px);
            z-index: 2;
        }

        .blog-card h3 {
            font-size: 1.3rem;
            font-weight: 800;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .blog-card p {
            color: var(--premium-grey-dark);
            font-size: 0.9rem;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .blog-newsletter {
            background-color: var(--premium-grey);
            padding: 80px 0;
            text-align: center;
            border-radius: 25px;
            margin-bottom: 80px;
        }

        .newsletter-content h2 {
            font-size: 2.2rem;
            font-weight: 900;
            margin-bottom: 15px;
        }

        .newsletter-form {
            max-width: 450px;
            margin: 30px auto 0;
            display: flex;
            gap: 10px;
        }

        .newsletter-form input {
            flex: 1;
            padding: 15px 20px;
            border-radius: 50px;
            border: 1px solid #ddd;
            font-family: inherit;
            outline: none;
            transition: var(--transition);
        }

        .newsletter-btn {
            background: var(--premium-black);
            color: #fff;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            cursor: pointer;
            transition: var(--transition);
        }

        @media (max-width: 1200px) {
            .blog-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 800px) {
            .featured-card {
                grid-template-columns: 1fr;
            }
            .blog-grid {
                grid-template-columns: 1fr;
            }
            .newsletter-form {
                flex-direction: column;
            }
        }
    </style>
@endsection

@section('main_content')
    <!-- Hero Section -->
    <section class="blog-header">
        <div class="container-blog">
            <span class="journal-tag">Luma Journal</span>
            <h1 class="journal-title">Đọc & Trải Nghiệm</h1>
            <p class="journal-subtitle">Khám phá những câu chuyện thú vị đằng sau những đôi chân không ngừng chuyển động.</p>

            <div class="blog-filters">
                <button class="filter-btn active">Tất cả</button>
                <button class="filter-btn">Sản phẩm mới</button>
                <button class="filter-btn">Văn hóa Sneaker</button>
                <button class="filter-btn">Hướng dẫn</button>
                <button class="filter-btn">Vệ sinh giày</button>
            </div>
        </div>
    </section>

    <div class="container-blog">
        <!-- Featured Post -->
        <article class="featured-card">
            <div class="featured-image">
                <span class="badge">Nổi bật</span>
            </div>
            <div class="featured-content">
                <div class="post-meta">
                    <span><i class='bx bx-calendar'></i> 11 Tháng 4, 2024</span>
                    <span><i class='bx bx-tag-alt'></i> Tương lai</span>
                </div>
                <h2>Định Hình Lại Tương Lai: Bộ Sưu Tập Luma Air Max 2024</h2>
                <p>Khám phá công nghệ đệm khí tiên tiến nhất cùng thiết kế vị lai trong bộ sưu tập mới nhất vừa đổ bộ tại Luma Shoes. Một bước tiến mạnh mẽ vào kỷ nguyên mới của sự thoải mái.</p>
                <a href="#" class="read-more-btn">
                    Đọc tiếp <i class='bx bx-right-arrow-alt'></i>
                </a>
            </div>
        </article>

        <!-- Blog Grid -->
        <div class="blog-grid">
            <!-- Post 1 -->
            <article class="blog-card">
                <div class="blog-media">
                    <span class="badge">Hướng dẫn</span>
                    <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&q=80&w=1000" alt="Style Guide">
                </div>
                <div class="post-meta">
                    <span><i class='bx bx-calendar'></i> 10 Tháng 4, 2024</span>
                </div>
                <h3>5 Cách Phối Đồ Với Sneakers Trắng Cho Mọi Dịp</h3>
                <p>Giày thể thao trắng là món đồ không thể thiếu. Nhưng làm sao để diện chúng thật phong cách từ công sở đến những buổi tiệc tối?</p>
                <a href="#" class="read-more-btn">Tìm hiểu <i class='bx bx-right-arrow-alt'></i></a>
            </article>

            <!-- Post 2 -->
            <article class="blog-card">
                <div class="blog-media">
                    <span class="badge">Vệ sinh</span>
                    <img src="https://images.unsplash.com/photo-1516478177764-9fe5bd7e9717?auto=format&fit=crop&q=80&w=1000" alt="Shoe Care">
                </div>
                <div class="post-meta">
                    <span><i class='bx bx-calendar'></i> 09 Tháng 4, 2024</span>
                </div>
                <h3>Bí Quyết Giữ Giày Luôn Như Mới: Cẩm Nang Vệ Sinh</h3>
                <p>Bạn lo lắng vết bẩn sẽ phá hỏng đôi giày yêu thích? Chúng tôi sẽ hướng dẫn các bước vệ sinh chuẩn chuyên gia tại nhà.</p>
                <a href="#" class="read-more-btn">Tìm hiểu <i class='bx bx-right-arrow-alt'></i></a>
            </article>

            <!-- Post 3 -->
            <article class="blog-card">
                <div class="blog-media">
                    <span class="badge">Văn hóa</span>
                    <img src="https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?auto=format&fit=crop&q=80&w=1000" alt="Sneaker Culture">
                </div>
                <div class="post-meta">
                    <span><i class='bx bx-calendar'></i> 08 Tháng 4, 2024</span>
                </div>
                <h3>Lịch Sử Của Air Jordan: Từ Sân Bóng Đến Biểu Tượng</h3>
                <p>Dòng giày đã thay đổi cả thế giới. Tìm hiểu về sự ra đời và tầm ảnh hưởng vĩ đại của Air Jordan.</p>
                <a href="#" class="read-more-btn">Tìm hiểu <i class='bx bx-right-arrow-alt'></i></a>
            </article>
        </div>

        <!-- Newsletter -->
        <div class="blog-newsletter">
            <div class="newsletter-content">
                <h2>Tham gia Luma Journal</h2>
                <p>Nhận những bài viết mới nhất và ưu đãi độc quyền.</p>
                <form class="newsletter-form" onsubmit="return false;">
                    <input type="email" placeholder="Email của bạn..." required>
                    <button type="submit" class="newsletter-btn">Đăng ký</button>
                </form>
            </div>
        </div>
    </div>
@endsection

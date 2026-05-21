@extends('Brand')

@section('brand-products')

    <style>
        /* ẩn danh sách thương hiệu */
        #brands-filter,
        #brand-list,
        .brand-hero {
            display: none !important;
        }

        .title {
            display: none !important;
        }

        :root {
            --mb-black: #111;
            --mb-white: #fff;
            --mb-grey-soft: #f9f9f9;
            --mb-grey: #999;
            --mb-border: #e8e8e8;
            --mb-danger: #c0392b;
            --mb-ease: all 0.32s cubic-bezier(.16, 1, .3, 1);
        }

        /* Breadcrumb */
        .mb-breadcrumb {
            background: var(--mb-grey-soft);
            border-bottom: 1px solid var(--mb-border);
            padding: 12px 0;
            margin-top: 72px;
        }

        .mb-breadcrumb-inner {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 40px;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .82rem;
            color: var(--mb-grey);
        }

        .mb-breadcrumb-inner a {
            color: var(--mb-grey);
            transition: color .2s;
        }

        .mb-breadcrumb-inner a:hover {
            color: var(--mb-black);
        }

        .mb-breadcrumb-inner .current {
            color: var(--mb-black);
            font-weight: 600;
        }

        .mb-breadcrumb-inner .sep {
            display: flex;
            align-items: center;
            font-size: 1rem;
        }

        /* Tiêu đề */
        .mb-hero {
            background: var(--mb-black);
            color: var(--mb-white);
            padding: 150px 0;
        }

        .mb-hero-inner {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 40px;
            display: flex;
            align-items: center;
            gap: 40px;
            flex-wrap: wrap;
        }

        .mb-hero-logo {
            width: 100px;
            height: 100px;
            object-fit: contain;
            background: white;
            border-radius: 12px;
            padding: 12px;
            flex-shrink: 0;
        }

        .mb-hero-text {
            flex: 1;
            min-width: 200px;
        }

        .mb-hero-name {
            font-size: 2.6rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 5px;
            margin-bottom: 10px;
            line-height: 1.1;
        }

        .mb-hero-desc {
            color: #aaa;
            font-size: .95rem;
            line-height: 1.6;
            max-width: 500px;
        }

        .mb-hero-stats {
            display: flex;
            gap: 20px;
            align-items: center;
            flex-shrink: 0;
        }

        .mb-stat {
            text-align: center;
        }

        .mb-stat-num {
            display: block;
            font-size: 1.8rem;
            font-weight: 800;
            line-height: 1;
        }

        .mb-stat-label {
            display: block;
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #777;
            margin-top: 4px;
        }

        .mb-stat-divider {
            width: 1px;
            height: 40px;
            background: #333;
        }

        /* Main */
        .mb-layout {
            max-width: 1300px;
            margin: 0 auto;
            padding: 40px 40px 100px;
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 50px;
            align-items: start;
        }

        /* Chọn lọc */
        .mb-sidebar {
            position: sticky;
            top: 90px;
        }

        .mb-sidebar-block {
            margin-bottom: 35px;
            padding-bottom: 35px;
            border-bottom: 1px solid var(--mb-border);
        }

        .mb-sidebar-block:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .sidebar-title {
            font-size: .75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--mb-black);
            margin-bottom: 16px;
        }

        /* Tìm kiếm thương hiệu */
        .mb-search-wrap {
            position: relative;
            margin-bottom: 12px;
        }

        .mb-search-wrap i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--mb-grey);
            font-size: 1rem;
        }

        #brand-search {
            width: 100%;
            padding: 10px 12px 10px 36px;
            border: 1px solid var(--mb-border);
            background: var(--mb-grey-soft);
            font-size: .85rem;
            outline: none;
            transition: var(--mb-ease);
            box-sizing: border-box;
        }

        #brand-search:focus {
            border-color: var(--mb-black);
            background: white;
        }

        .sidebar-list {
            max-height: 200px;
            overflow-y: auto;
            padding: 0;
        }

        .sidebar-list::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-list::-webkit-scrollbar-thumb {
            background: #ccc;
        }

        .sidebar-link {
            display: block;
            padding: 9px 14px;
            color: var(--mb-grey);
            font-size: .85rem;
            transition: var(--mb-ease);
            border-left: 2px solid transparent;
        }

        .sidebar-link:hover,
        .sidebar-link.active-brand {
            color: var(--mb-black);
            border-left-color: var(--mb-black);
            padding-left: 18px;
            background: var(--mb-grey-soft);
        }

        /* lọc giới tính */
        .btn-group-vertical {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .gender-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 18px;
            font-size: .85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            border: 1px solid var(--mb-border);
            background: transparent;
            cursor: pointer;
            transition: var(--mb-ease);
            color: var(--mb-grey);
            text-align: left;
        }

        .gender-btn:hover {
            border-color: var(--mb-black);
            color: var(--mb-black);
            background: var(--mb-grey-soft);
        }

        .gender-btn.active {
            background: var(--mb-black);
            color: white;
            border-color: var(--mb-black);
        }

        /* khoảng giá */
        .mb-price-filter {}

        .mb-price-row {
            display: flex;
            justify-content: space-between;
            font-size: .8rem;
            color: var(--mb-grey);
            margin-bottom: 10px;
            font-weight: 600;
        }

        .mb-range {
            width: 100%;
            accent-color: var(--mb-black);
            margin-bottom: 14px;
            cursor: pointer;
        }

        .mb-apply-price {
            width: 100%;
            padding: 10px;
            background: var(--mb-black);
            color: white;
            border: none;
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: var(--mb-ease);
        }

        .mb-apply-price:hover {
            background: #444;
        }

        /* Sizes */
        .mb-sizes {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .mb-size-btn {
            padding: 7px 13px;
            border: 1px solid var(--mb-border);
            background: white;
            font-size: .82rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--mb-ease);
            color: var(--mb-grey);
        }

        .mb-size-btn:hover,
        .mb-size-btn.active {
            background: var(--mb-black);
            color: white;
            border-color: var(--mb-black);
        }

        /* Thanh công cụ - toolbar */
        .mb-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--mb-border);
            gap: 20px;
            flex-wrap: wrap;
        }

        .mb-result-count {
            font-size: .85rem;
            color: var(--mb-grey);
            margin: 0;
        }

        .mb-result-count strong {
            color: var(--mb-black);
        }

        .mb-toolbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .mb-sort {
            padding: 9px 14px;
            border: 1px solid var(--mb-border);
            background: white;
            font-size: .85rem;
            outline: none;
            cursor: pointer;
            transition: var(--mb-ease);
        }

        .mb-sort:focus {
            border-color: var(--mb-black);
        }

        .mb-view-toggle {
            display: flex;
            gap: 5px;
        }

        .mb-view-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--mb-border);
            background: white;
            font-size: 1.1rem;
            cursor: pointer;
            transition: var(--mb-ease);
            color: var(--mb-grey);
        }

        .mb-view-btn.active,
        .mb-view-btn:hover {
            background: var(--mb-black);
            color: white;
            border-color: var(--mb-black);
        }

        /* Hiển thị sản phẩm */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .products-grid.list-view {
            grid-template-columns: 1fr;
        }

        .products-grid.list-view .product-item {
            width: 100%;
        }

        .products-grid.list-view .product-card {
            display: flex;
            flex-direction: row;
        }

        .products-grid.list-view .product-image-wrapper {
            width: 200px;
            flex-shrink: 0;
            height: auto;
            border-bottom: none;
            border-right: 1px solid var(--mb-border);
        }

        .products-grid.list-view .card-img-top {
            height: 100%;
            object-fit: cover;
        }

        /* Thẻ sản phẩm */
        .product-card {
            position: relative;
            border: 1px solid var(--mb-border);
            overflow: hidden;
            transition: var(--mb-ease);
            background: white;
            border-radius: 0;
        }

        .product-card:hover {
            border-color: var(--mb-black);
            box-shadow: 0 12px 40px rgba(0, 0, 0, .08);
            transform: translateY(-3px);
        }

        .product-image-wrapper {
            width: 100%;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--mb-grey-soft);
            border-bottom: 1px solid var(--mb-border);
            overflow: hidden;
            position: relative;
        }

        .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .5s ease;
        }

        .product-card:hover .card-img-top {
            transform: scale(1.06);
        }

        /* Wishlist */
        .wishlist {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 34px;
            height: 34px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 20;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .1);
            transition: var(--mb-ease);
        }

        .wishlist:hover {
            transform: scale(1.1);
        }

        .wishlist-icon {
            font-size: 1rem;
            color: #ccc;
            transition: color .2s;
        }

        .wishlist-icon.active {
            color: var(--mb-danger) !important;
        }

        /* Giới tính */
        .mb-gender-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            font-size: .65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 3px 10px;
            z-index: 10;
        }

        .badge-men {
            background: #e8f0fe;
            color: #1a73e8;
        }

        .badge-women {
            background: #fce4ec;
            color: #c2185b;
        }

        .badge-unisex {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        /* Thẻ sản phẩm */
        .card-body {
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .brand-title {
            font-size: .72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--mb-grey);
            margin: 0;
        }

        .product-title {
            font-size: .92rem;
            font-weight: 600;
            color: var(--mb-black);
            margin: 0;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-title a {
            color: inherit;
            transition: color .2s;
        }

        .product-title a:hover {
            color: #555;
        }

        /* Size giày */
        .mb-size-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .mb-chip {
            padding: 2px 7px;
            border: 1px solid var(--mb-border);
            font-size: .68rem;
            font-weight: 600;
            color: var(--mb-grey);
        }

        .mb-chip-more {
            padding: 2px 7px;
            font-size: .68rem;
            color: var(--mb-grey);
            font-style: italic;
        }

        /* giá */
        .price-section {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .discounted-price {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--mb-black);
        }

        /* thêm vào giỏ hàng*/
        .add-to-cart-btn {
            width: 100%;
            padding: 10px 0;
            background: var(--mb-black);
            color: white;
            border: none;
            font-size: .78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            cursor: pointer;
            transition: var(--mb-ease);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 4px;
        }

        .add-to-cart-btn:hover {
            background: #444;
            transform: none;
        }

        .add-to-cart-btn.adding {
            background: #27ae60 !important;
        }

        /* thông báo không tìm thấy sản phẩm */
        .empty-state-default,
        .empty-state-filter {
            grid-column: 1 / -1;
            padding: 80px 20px;
            text-align: center;
            color: var(--mb-grey);
            background: var(--mb-grey-soft);
            border: 1px dashed var(--mb-border);
        }

        .empty-state-default i,
        .empty-state-filter i {
            font-size: 3rem;
            display: block;
            margin-bottom: 15px;
            opacity: .4;
        }

        .mb-browse-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 30px;
            background: var(--mb-black);
            color: white;
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border: none;
            cursor: pointer;
            transition: var(--mb-ease);
            text-decoration: none;
        }

        .mb-browse-btn:hover {
            background: #444;
            color: white;
        }

        /* Responsive */
        @media (max-width: 1100px) {
            .mb-layout {
                grid-template-columns: 220px 1fr;
                gap: 30px;
            }

            .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .mb-layout {
                grid-template-columns: 1fr;
                padding: 20px;
            }

            .mb-sidebar {
                position: static;
            }

            .mb-hero-inner {
                flex-direction: column;
                text-align: center;
            }

            .mb-hero-name {
                font-size: 2rem;
            }

            .mb-hero-stats {
                justify-content: center;
                width: 100%;
            }

            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    {{-- Tiêu đề --}}
    <div class="mb-hero">
        <div class="mb-hero-inner">
            @if ($brand->logo ?? false)
                <img src="{{ $brand->logo }}" alt="{{ $brand->name }}" class="mb-hero-logo"
                    onerror="this.style.display='none'">
            @endif
            <div class="mb-hero-text">
                <h1 class="mb-hero-name">{{ $brand->name }}</h1>
                <p class="mb-hero-desc">Khám phá bộ sưu tập sneaker &amp; giày thời thượng chính hãng từ
                    <strong>{{ $brand->name }}</strong>. Phong cách đỉnh cao — phù hợp mọi dịp.
                </p>
            </div>
            <div class="mb-hero-stats">
                <div class="mb-stat">
                    <span class="mb-stat-num" id="product-count-total">{{ $products->count() }}</span>
                    <span class="mb-stat-label">Sản phẩm</span>
                </div>
                <div class="mb-stat-divider"></div>
                <div class="mb-stat">
                    <span class="mb-stat-num">100%</span>
                    <span class="mb-stat-label">Chính hãng</span>
                </div>
                <div class="mb-stat-divider"></div>
                <div class="mb-stat">
                    <span class="mb-stat-num">Free</span>
                    <span class="mb-stat-label">Giao hàng</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Main --}}
    <div class="mb-layout">

        {{-- Sidebar --}}
        <aside class="mb-sidebar">

            {{-- Thương Hiệu --}}
            <div class="mb-sidebar-block">
                <h6 class="sidebar-title">Thương Hiệu</h6>
                <div class="mb-search-wrap">
                    <i class="bx bx-search"></i>
                    <input type="text" id="brand-search" placeholder="Tìm thương hiệu..." autocomplete="off">
                </div>
                <ul class="list-unstyled sidebar-list" id="brand-list-filter">
                    @forelse($allBrands as $relatedBrand)
                        <li>
                            <a href="{{ route('brands.show', $relatedBrand->name) }}"
                                class="sidebar-link {{ $relatedBrand->name === $brand->name ? 'active-brand' : '' }}">
                                {{ $relatedBrand->name }}
                            </a>
                        </li>
                    @empty
                        <li class="text-muted" style="padding:10px 15px;font-size:.85rem;">Chưa có thương hiệu.</li>
                    @endforelse
                </ul>
            </div>

            {{-- Giới Tính --}}
            <div class="mb-sidebar-block">
                <h6 class="sidebar-title">Giới Tính</h6>
                <div class="btn-group-vertical w-100" role="group">
                    <button type="button" class="btn btn-outline-secondary gender-btn active" data-gender="all">
                        <i class="bx bx-group"></i> Tất cả
                    </button>
                    <button type="button" class="btn btn-outline-secondary gender-btn" data-gender="Men">
                        <i class="bx bx-male"></i> Nam
                    </button>
                    <button type="button" class="btn btn-outline-secondary gender-btn" data-gender="Women">
                        <i class="bx bx-female"></i> Nữ
                    </button>
                    <button type="button" class="btn btn-outline-secondary gender-btn" data-gender="Unisex">
                        <i class="bx bx-intersect"></i> Unisex
                    </button>
                </div>
            </div>

            {{-- Khoảng Giá --}}
            <div class="mb-sidebar-block">
                <h6 class="sidebar-title">Khoảng Giá</h6>
                <div class="mb-price-filter">
                    <div class="mb-price-row">
                        <span id="price-label-min">800.000 ₫</span>
                        <span id="price-label-max">5.000.000 ₫</span>
                    </div>
                    <input type="range" id="price-slider-max" min="800000" max="5000000" step="100000" value="5000000"
                        class="mb-range">
                    <button class="mb-apply-price" id="apply-price">Áp dụng</button>
                </div>
            </div>

            {{-- Size giày --}}
            <div class="mb-sidebar-block">
                <h6 class="sidebar-title">Size</h6>
                <div class="mb-sizes" id="size-filter">
                    @foreach (['38', '39', '40', '41', '42', '43', '44'] as $s)
                        <button class="mb-size-btn" data-size="{{ $s }}">{{ $s }}</button>
                    @endforeach
                </div>
            </div>

        </aside>

        {{-- Sản phẩm --}}
        <section class="mb-content">

            {{-- Thanh công cụ - Toolbar --}}
            <div class="mb-toolbar">
                <p class="mb-result-count">
                    Hiển thị <strong id="visible-count">{{ $products->count() }}</strong> sản phẩm
                </p>
                <div class="mb-toolbar-right">
                    {{-- Sắp xếp giá --}}
                    <select class="mb-sort" id="sort-select">
                        <option value="default">Mặc định</option>
                        <option value="price-asc">Giá: Thấp → Cao</option>
                        <option value="price-desc">Giá: Cao → Thấp</option>
                        <option value="name-asc">Tên A → Z</option>
                    </select>
                    {{-- Chuyển đổi chế độ xem --}}
                    <div class="mb-view-toggle">
                        <button class="mb-view-btn active" id="view-grid" title="Grid"><i
                                class="bx bx-grid-alt"></i></button>
                        <button class="mb-view-btn" id="view-list" title="List"><i class="bx bx-list-ul"></i></button>
                    </div>
                </div>
            </div>

            {{-- Hiển thị sản phẩm --}}
            <div class="products-grid" id="products-filter">
                @forelse($products as $product)
                    <div class="product-item" data-gender="{{ strtolower($product->gender) }}"
                        data-price="{{ $product->price }}" data-name="{{ strtolower($product->name) }}"
                        data-sizes="{{ $product->sizes->pluck('size')->implode(',') }}">
                        <div class="product-card card h-100">

                            <div class="product-image-wrapper">
                                {{-- Thêm vào danh sách yêu thích --}}
                                <div class="wishlist" onclick="toggleWishlist(this)" title="Yêu thích">
                                    <i class="bx bxs-heart wishlist-icon"></i>
                                </div>
                                {{-- Giới tính --}}
                                <span class="mb-gender-badge badge-{{ strtolower($product->gender) }}">
                                    {{ $product->gender === 'Men' ? 'Nam' : ($product->gender === 'Women' ? 'Nữ' : 'Unisex') }}
                                </span>

                                <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none">
                                    <img src="{{ $product->image ?? asset('img/placeholder-product.png') }}"
                                        class="card-img-top" alt="{{ $product->name }}" loading="lazy"
                                        onerror="this.src='https://placehold.co/300x300/e8e8e8/888?text=No+Image'">
                                </a>
                            </div>

                            <div class="card-body">
                                <h4 class="brand-title">{{ $brand->name }}</h4>
                                <h5 class="product-title">
                                    <a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a>
                                </h5>

                                {{-- Size --}}
                                @if ($product->sizes->isNotEmpty())
                                    <div class="mb-size-chips">
                                        @foreach ($product->sizes->take(4) as $sz)
                                            <span class="mb-chip">{{ $sz->size }}</span>
                                        @endforeach
                                        @if ($product->sizes->count() > 4)
                                            <span class="mb-chip-more">+{{ $product->sizes->count() - 4 }}</span>
                                        @endif
                                    </div>
                                @endif

                                <div class="price-section">
                                    <div class="discounted-price">{{ \App\Helpers\helpers::format($product->price) }}
                                    </div>
                                </div>

                                <form action="{{ route('cart.add', $product->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="add-to-cart-btn">
                                        <i class="fa fa-shopping-cart"></i>
                                        Thêm vào giỏ
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state-default">
                        <i class="bx bx-package"></i>
                        <p>Chưa có sản phẩm nào cho thương hiệu <strong>{{ $brand->name }}</strong>.</p>
                        <a href="{{ route('products.list') }}" class="mb-browse-btn">Xem tất cả sản phẩm</a>
                    </div>
                @endforelse

                {{-- Không có sản phẩm phù hợp với bộ lọc --}}
                <div class="empty-state-filter" style="display:none;" id="empty-filter">
                    <i class="bx bx-filter-alt"></i>
                    <p>Không có sản phẩm phù hợp với bộ lọc.</p>
                    <button class="mb-browse-btn" id="reset-filters">Xoá bộ lọc</button>
                </div>
            </div>

        </section>
    </div>

    {{-- Scripts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /* Gọi các biến */
            const items = document.querySelectorAll('.product-item');
            const emptyFilter = document.getElementById('empty-filter');
            const countEl = document.getElementById('visible-count');
            const grid = document.getElementById('products-filter');
            const genderBtns = document.querySelectorAll('.gender-btn');
            const sizeBtns = document.querySelectorAll('#size-filter .mb-size-btn');

            let activeGender = 'all';
            let activeSize = null;
            let maxPrice = 5000000;

            /* Đếm số sản phẩm */
            function updateCount() {
                let n = 0;
                items.forEach(i => {
                    if (i.style.display !== 'none') n++;
                });
                countEl.textContent = n;
                emptyFilter.style.display = n === 0 ? 'flex' : 'none';
                if (n === 0) emptyFilter.style.flexDirection = 'column';
            }

            /* Áp dụng bộ lọc */
            function applyFilters() {
                items.forEach(item => {
                    const g = item.dataset.gender.toLowerCase();
                    const p = parseInt(item.dataset.price) || 0;
                    const s = (item.dataset.sizes || '').split(',');

                    const genderOk = activeGender === 'all' || g === activeGender.toLowerCase();
                    const priceOk = p <= maxPrice;
                    const sizeOk = !activeSize || s.includes(activeSize);

                    item.style.display = (genderOk && priceOk && sizeOk) ? '' : 'none';
                });
                updateCount();
            }

            /* Tìm kiếm thương hiệu */
            document.getElementById('brand-search').addEventListener('input', function() {
                const q = this.value.toLowerCase();
                document.querySelectorAll('#brand-list-filter li').forEach(li => {
                    const txt = li.textContent.toLowerCase();
                    li.style.display = txt.includes(q) ? '' : 'none';
                });
            });

            /* Bộ lọc giới tính */
            genderBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    genderBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    activeGender = this.dataset.gender;
                    applyFilters();
                });
            });

            /* Bộ lọc size giày */
            sizeBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    if (this.classList.contains('active')) {
                        this.classList.remove('active');
                        activeSize = null;
                    } else {
                        sizeBtns.forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        activeSize = this.dataset.size;
                    }
                    applyFilters();
                });
            });

            /* Bộ lọc giá tiền */
            const slider = document.getElementById('price-slider-max');
            const labelMax = document.getElementById('price-label-max');
            slider.addEventListener('input', function() {
                labelMax.textContent = parseInt(this.value).toLocaleString('vi-VN') + ' ₫';
            });
            document.getElementById('apply-price').addEventListener('click', function() {
                maxPrice = parseInt(slider.value);
                applyFilters();
            });

            /* Sắp xếp sản phẩm */
            document.getElementById('sort-select').addEventListener('change', function() {
                const val = this.value;
                const arr = Array.from(items);
                const parent = grid;

                arr.sort((a, b) => {
                    if (val === 'price-asc') return parseInt(a.dataset.price) - parseInt(b.dataset
                        .price);
                    if (val === 'price-desc') return parseInt(b.dataset.price) - parseInt(a.dataset
                        .price);
                    if (val === 'name-asc') return a.dataset.name.localeCompare(b.dataset.name);
                    return 0;
                });

                arr.forEach(el => parent.appendChild(el));
            });

            /* Chuyển đổi chế độ xem */
            document.getElementById('view-grid').addEventListener('click', function() {
                grid.classList.remove('list-view');
                document.getElementById('view-list').classList.remove('active');
                this.classList.add('active');
            });
            document.getElementById('view-list').addEventListener('click', function() {
                grid.classList.add('list-view');
                document.getElementById('view-grid').classList.remove('active');
                this.classList.add('active');
            });

            /* Xoá bộ lọc*/
            document.getElementById('reset-filters')?.addEventListener('click', function() {
                activeGender = 'all';
                activeSize = null;
                maxPrice = 5000000;
                slider.value = 5000000;
                labelMax.textContent = '5.000.000 ₫';
                genderBtns.forEach(b => b.classList.remove('active'));
                genderBtns[0].classList.add('active');
                sizeBtns.forEach(b => b.classList.remove('active'));
                document.getElementById('sort-select').value = 'default';
                items.forEach(i => i.style.display = '');
                updateCount();
            });

            /* Thêm vào danh sách yêu thích */
            window.toggleWishlist = function(el) {
                el.querySelector('.wishlist-icon').classList.toggle('active');
            };

            /* Thêm vào giỏ hàng */
            document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    this.classList.add('adding');
                    setTimeout(() => this.classList.remove('adding'), 1000);
                });
            });

            updateCount();
        });
    </script>

@endsection

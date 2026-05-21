@extends('Product')

@push('MasterCSS')
    <style>
        :root {
            --mp-black: #111;
            --mp-white: #fff;
            --mp-soft: #f8f8f8;
            --mp-border: #e8e8e8;
            --mp-grey: #999;
            --mp-ease: all .35s cubic-bezier(.16, 1, .3, 1);
        }

        body {
            background: #f9f9f9;
            color: var(--mp-black);
        }

        /* Tiêu đề */
        .hero-premium {
            position: relative;
            height: 450px;
            max-width: 1100px;
            margin: 80px auto 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--mp-black);
            overflow: hidden;
        }

        .hero-bg-overlay {
            position: absolute;
            inset: 0;
            background: url('https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=2000&auto=format&fit=crop') center/cover no-repeat;
            opacity: .6;
            filter: grayscale(100%);
            transform: scale(1.08);
            z-index: 1;
        }

        .hero-premium:hover .hero-bg-overlay {
            transform: scale(1.12);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: var(--mp-white);
        }

        .hero-title {
            font-size: 80px;
            font-weight: 900;
            letter-spacing: -2px;
            text-transform: uppercase;
            margin-bottom: 5px;
            line-height: 1;
            font-style: italic;
        }

        .hero-breadcrumb {
            font-size: 15px;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 500;
            color: var(--mp-white);
            opacity: 1;
        }

        /* thanh công cụ */
        .mp-toolbar-bar {
            border-bottom: 1px solid var(--mp-border);
            background: var(--mp-soft);
            margin-bottom: 30px;
            padding-bottom: 20px;
        }

        .mp-toolbar-bar-inner {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 56px;
            gap: 20px;
        }

        .mp-result-count {
            font-size: .82rem;
            color: var(--mp-grey);
        }

        .mp-result-count strong {
            color: var(--mp-black);
        }

        .mp-toolbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .mp-sort {
            padding: 8px 14px;
            border: 1px solid var(--mp-border);
            background: var(--mp-white);
            font-size: .82rem;
            font-family: inherit;
            outline: none;
            cursor: pointer;
            transition: var(--mp-ease);
        }

        .mp-sort:focus {
            border-color: var(--mp-black);
        }

        .mp-view-toggle {
            display: flex;
            gap: 4px;
        }

        .mp-view-btn {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--mp-border);
            background: var(--mp-white);
            font-size: 1.05rem;
            cursor: pointer;
            transition: var(--mp-ease);
            color: var(--mp-grey);
        }

        .mp-view-btn.active,
        .mp-view-btn:hover {
            background: var(--mp-black);
            color: var(--mp-white);
            border-color: var(--mp-black);
        }

        /* Hiển thị sản phẩm */
        .luma-container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 40px;
            box-sizing: border-box;
        }

        .main-grid-layout {
            display: flex;
            gap: 40px;
            padding: 20px 0 120px;
            align-items: flex-start;
        }

        /* Thanh bên */
        .sidebar-wrapper {
            width: 220px;
            flex-shrink: 0;
            position: sticky;
            top: 90px;
        }

        .filter-section {
            margin-bottom: 36px;
            padding-bottom: 36px;
            border-bottom: 1px solid var(--mp-border);
        }

        .filter-section:last-child {
            border-bottom: none;
        }

        .filter-header {
            font-size: .72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            color: var(--mp-black);
            margin-bottom: 16px;
        }

        /* Tìm kiếm */
        .mp-search-wrap {
            position: relative;
        }

        .mp-search-wrap i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--mp-grey);
            font-size: 1rem;
            pointer-events: none;
        }

        .premium-input {
            width: 100%;
            padding: 11px 14px 11px 36px;
            border: 1px solid var(--mp-border);
            background: var(--mp-soft);
            font-size: .85rem;
            font-family: inherit;
            outline: none;
            transition: var(--mp-ease);
            box-sizing: border-box;
        }

        .premium-input:focus {
            border-color: var(--mp-black);
            background: var(--mp-white);
        }

        /* Danh sách thương hiệu */
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 220px;
            overflow-y: auto;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 3px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: #ddd;
        }

        .sidebar-menu-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0 10px 14px;
            color: var(--mp-grey);
            font-size: .85rem;
            font-weight: 500;
            border-left: 2px solid transparent;
            transition: var(--mp-ease);
        }

        .sidebar-menu-link:hover,
        .sidebar-menu-link.active {
            color: var(--mp-black);
            border-left-color: var(--mp-black);
            padding-left: 18px;
            background: var(--mp-soft);
        }

        /* Giới tính */
        .pill-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .pill-option {
            padding: 11px 18px;
            border: 1px solid var(--mp-border);
            font-size: .82rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            cursor: pointer;
            transition: var(--mp-ease);
            color: var(--mp-grey);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pill-option:hover {
            border-color: var(--mp-black);
            color: var(--mp-black);
            background: var(--mp-soft);
        }

        .pill-option.active {
            background: var(--mp-black);
            color: var(--mp-white);
            border-color: var(--mp-black);
        }

        /* Khoảng giá */
        .mp-price-row {
            display: flex;
            justify-content: space-between;
            font-size: .78rem;
            color: var(--mp-grey);
            font-weight: 600;
            margin-bottom: 10px;
        }

        .mp-range {
            width: 100%;
            accent-color: var(--mp-black);
            cursor: pointer;
            margin-bottom: 14px;
        }

        .mp-apply-price {
            width: 100%;
            padding: 10px;
            background: var(--mp-black);
            color: var(--mp-white);
            border: none;
            font-family: inherit;
            font-size: .76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: var(--mp-ease);
        }

        .mp-apply-price:hover {
            background: #444;
        }

        /* Size giày */
        .mp-sizes {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .mp-size-btn {
            padding: 6px 12px;
            border: 1px solid var(--mp-border);
            background: var(--mp-white);
            font-size: .8rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--mp-ease);
            color: var(--mp-grey);
        }

        .mp-size-btn:hover,
        .mp-size-btn.active {
            background: var(--mp-black);
            color: var(--mp-white);
            border-color: var(--mp-black);
        }

        .content-wrapper {
            flex-grow: 1;
            min-width: 0;
        }

        /* Hiển thị sản phẩm */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            width: 100%;
        }

        .products-grid.list-view {
            grid-template-columns: 1fr;
        }

        .products-grid.list-view .product-card {
            display: flex;
            flex-direction: row;
        }

        .products-grid.list-view .product-image-wrapper {
            width: 220px;
            flex-shrink: 0;
            height: auto;
            border-bottom: none;
            border-right: 1px solid var(--mp-border);
        }

        .products-grid.list-view .card-img-top {
            height: 100%;
            object-fit: cover;
        }

        .products-grid.list-view .card-body {
            padding: 25px;
        }

        .product-card {
            position: relative;
            border: 1px solid var(--mp-border);
            overflow: hidden;
            transition: var(--mp-ease);
            background: white;
        }

        .product-card:hover {
            border-color: var(--mp-black);
            box-shadow: 0 12px 40px rgba(0, 0, 0, .08);
            transform: translateY(-3px);
        }

        /* Ảnh sản phẩm */
        .product-image-wrapper {
            width: 100%;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--mp-soft);
            border-bottom: 1px solid var(--mp-border);
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
            transition: var(--mp-ease);
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
            color: #c0392b !important;
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
            border-radius: 20px;
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

        /* thông tin sản phẩm */
        .card-body {
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 7px;
            flex-grow: 1;
            /* Cần thiết để h-100 của bootstrap hoạt động chuẩn */
        }

        .brand-title {
            font-size: .72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--mp-grey);
            margin: 0;
        }

        .product-title {
            font-size: .92rem;
            font-weight: 600;
            color: var(--mp-black);
            margin: 0;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 1.4em;
            /* Đảm bảo luôn chiếm 1 dòng */
        }

        .product-title a {
            color: inherit;
            transition: color .2s;
        }

        .product-title a:hover {
            color: #555;
        }

        /* Size */
        .mb-size-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            height: 24px;
            /* Cố định chiều cao phần size để tránh vỡ form khi sp không có size hoặc có 1 hàng */
            overflow: hidden;
        }

        .mb-chip {
            padding: 2px 7px;
            border: 1px solid var(--mp-border);
            font-size: .68rem;
            font-weight: 600;
            color: var(--mp-grey);
        }

        .mb-chip-more {
            padding: 2px 7px;
            font-size: .68rem;
            color: var(--mp-grey);
            font-style: italic;
        }

        /* giá sản phẩm*/
        .price-section {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .discounted-price {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--mp-black);
        }

        /* Thêm vào giỏ hàng */
        .add-to-cart-btn {
            width: 100%;
            padding: 10px 0;
            background: var(--mp-black);
            color: white;
            border: none;
            font-size: .78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            cursor: pointer;
            transition: var(--mp-ease);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: auto;
            /* Đẩy xuống đáy card-body */
        }

        .add-to-cart-btn:hover {
            background: #444;
        }

        /* thông báo không tìm thấy sp */
        .empty-state-default,
        .empty-state-filter {
            grid-column: 1 / -1;
            padding: 80px 20px;
            text-align: center;
            color: var(--mp-grey);
            background: var(--mp-soft);
            border: 1px dashed var(--mp-border);
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
            background: var(--mp-black);
            color: white;
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border: none;
            cursor: pointer;
            transition: var(--mp-ease);
            text-decoration: none;
        }

        .mb-browse-btn:hover {
            background: #444;
            color: white;
        }

        /* phân trang */
        .pagination-premium {
            margin-top: 70px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .page-link-premium {
            width: 46px;
            height: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--mp-border);
            background: var(--mp-white);
            color: var(--mp-black);
            font-weight: 600;
            font-size: .88rem;
            transition: var(--mp-ease);
            text-decoration: none;
            cursor: pointer;
        }

        .page-link-premium:hover:not(.disabled):not(.active):not(.dots) {
            border-color: var(--mp-black);
            background: var(--mp-soft);
        }

        .page-link-premium.active {
            background: var(--mp-black);
            color: var(--mp-white);
            border-color: var(--mp-black);
        }

        .page-link-premium.disabled {
            opacity: .25;
            cursor: not-allowed;
        }

        .page-link-premium.dots {
            border: none;
            background: transparent;
            cursor: default;
        }

        /* Responsive */
        @media (max-width: 1300px) {
            .products-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 1050px) {
            .main-grid-layout {
                gap: 30px;
            }

            .sidebar-wrapper {
                width: 230px;
            }

            .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 860px) {
            .main-grid-layout {
                flex-direction: column;
            }

            .sidebar-wrapper {
                width: 100%;
                position: static;
            }

            .products-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .luma-container {
                padding: 0 20px;
            }

            .mp-toolbar-bar-inner {
                padding: 0 20px;
            }

            .hero-premium {
                height: 240px;
                margin-top: 60px;
            }

            .hero-title {
                font-size: 2rem;
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

            .sidebar-wrapper {
                margin-bottom: 10px;
            }
        }
    </style>
@endpush

@section('master')

    {{-- Tiêu đề --}}
    <div class="hero-premium">
        <div class="hero-bg-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">{{ $title ?? 'Sản Phẩm' }}</h1>
            <div class="hero-breadcrumb">LUMA SHOES / {{ strtoupper($title ?? 'DANH MỤC') }}</div>
        </div>
    </div>

    {{-- Thanh công cụ --}}


    {{-- Main --}}
    <div class="luma-container">
        <main class="main-grid-layout">

            {{-- Sidebar --}}
            <aside class="sidebar-wrapper">

                {{-- Search --}}
                <div class="filter-section">
                    <div class="filter-header">Tìm kiếm</div>
                    <div class="mp-search-wrap">
                        <i class="bx bx-search"></i>
                        <input type="text" id="brand-search" class="premium-input"
                            placeholder="Nhập tên giày, thương hiệu...">
                    </div>
                </div>

                {{-- Thương hiệu --}}
                <div class="filter-section">
                    <div class="filter-header">Thương Hiệu</div>
                    <ul class="sidebar-menu" id="brand-list-filter">
                        <li>
                            <a href="{{ route('products.list') }}"
                                class="sidebar-menu-link {{ !isset($active_brand) ? 'active' : '' }}">
                                <span>Tất cả</span>
                            </a>
                        </li>
                        @foreach ($allBrands as $relatedBrand)
                            <li>
                                <a href="{{ route('products.list', ['brand' => $relatedBrand->id]) }}"
                                    class="sidebar-menu-link {{ isset($active_brand) && $active_brand == $relatedBrand->id ? 'active' : '' }}">
                                    <span>{{ $relatedBrand->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Giới tính --}}
                <div class="filter-section">
                    <div class="filter-header">Giới Tính</div>
                    <div class="pill-group">
                        <div class="pill-option {{ $active_gender == 'all' || !isset($active_gender) ? 'active' : '' }}"
                            onclick="location.href='{{ route('products.list') }}'">
                            <i class="bx bx-group"></i> Tất cả
                        </div>
                        @php $genders = ['Men' => ['label'=>'Nam','icon'=>'bx-male'], 'Women' => ['label'=>'Nữ','icon'=>'bx-female'], 'Unisex' => ['label'=>'Unisex','icon'=>'bx-intersect']]; @endphp
                        @foreach ($genders as $key => $info)
                            <div class="pill-option {{ isset($active_gender) && $active_gender == $key ? 'active' : '' }}"
                                onclick="location.href='{{ route('products.gender', $key) }}'">
                                <i class="bx {{ $info['icon'] }}"></i> {{ $info['label'] }}
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Khoản giá --}}
                <div class="filter-section">
                    <div class="filter-header">Khoảng Giá</div>
                    <div class="mp-price-row">
                        <span>800.000 ₫</span>
                        <span id="price-label-max">5.000.000 ₫</span>
                    </div>
                    <input type="range" class="mp-range" id="price-slider-max" min="800000" max="5000000"
                        step="100000" value="5000000">
                    <button class="mp-apply-price" id="apply-price">Áp dụng</button>
                </div>

                {{-- Size --}}
                <div class="filter-section">
                    <div class="filter-header">Size</div>
                    <div class="mp-sizes" id="size-filter">
                        @foreach (['38', '39', '40', '41', '42', '43', '44'] as $s)
                            <button class="mp-size-btn" data-size="{{ $s }}">{{ $s }}</button>
                        @endforeach
                    </div>
                </div>

            </aside>

            {{-- Hiển thị sản phẩm --}}
            <section class="content-wrapper">
                <div class="mp-toolbar-bar">
                    <div class="mp-toolbar-bar-inner">
                        <p class="mp-result-count" style="margin:0">
                            Hiển thị <strong id="visible-count">{{ $products->count() }}</strong> sản phẩm
                        </p>
                        <div class="mp-toolbar-right">
                            <select class="mp-sort" id="sort-select">
                                <option value="default">Mặc định</option>
                                <option value="price-asc">Giá: Thấp → Cao</option>
                                <option value="price-desc">Giá: Cao → Thấp</option>
                                <option value="name-asc">Tên A → Z</option>
                            </select>
                            <div class="mp-view-toggle">
                                <button class="mp-view-btn active" id="view-grid" title="Grid view"><i
                                        class="bx bx-grid-alt"></i></button>
                                <button class="mp-view-btn" id="view-list" title="List view"><i
                                        class="bx bx-list-ul"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="products-grid" id="products-filter">

                    @forelse($products as $product)
                        <div class="product-item" data-gender="{{ strtolower($product->gender) }}"
                            data-brand="{{ strtolower($product->brand->name ?? '') }}"
                            data-name="{{ strtolower($product->name) }}" data-price="{{ $product->price }}"
                            data-sizes="{{ $product->sizes->pluck('size')->implode(',') }}">
                            <div class="product-card card h-100">

                                <div class="product-image-wrapper">
                                    {{-- Wishlist --}}
                                    <div class="wishlist" onclick="toggleWishlist(this)" title="Yêu thích">
                                        <i class="bx bxs-heart wishlist-icon"></i>
                                    </div>
                                    {{-- Giới tính --}}
                                    <span class="mb-gender-badge badge-{{ strtolower($product->gender) }}">
                                        {{ $product->gender === 'Men' ? 'Nam' : ($product->gender === 'Women' ? 'Nữ' : 'Unisex') }}
                                    </span>

                                    {{-- Hình ảnh (Click để Quick View) --}}
                                    <div style="cursor:pointer;"
                                        onclick="if(typeof openQuickView === 'function') openQuickView({{ $product->id }})">
                                        <img src="{{ $product->image ?? asset('img/placeholder-product.png') }}"
                                            class="card-img-top" alt="{{ $product->name }}" loading="lazy"
                                            onerror="this.src='https://placehold.co/300x300/e8e8e8/888?text=No+Image'">
                                    </div>
                                </div>

                                {{-- Thông tin sản phẩm --}}
                                <div class="card-body">
                                    <h4 class="brand-title">{{ $product->brand->name ?? 'Sneaker' }}</h4>
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

                                    {{-- Giá sản phẩm --}}
                                    <div class="price-section">
                                        <div class="discounted-price">{{ \App\Helpers\helpers::format($product->price) }}
                                        </div>
                                    </div>

                                    {{-- Thêm vào giỏ hàng --}}
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST"
                                        style="margin:0;">
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
                        {{-- không tìm thấy sp --}}
                        <div class="empty-state-default">
                            <i class="bx bx-search-alt"></i>
                            <p>Không tìm thấy sản phẩm nào.</p>
                            <a href="{{ route('products.list') }}" class="mb-browse-btn">Xem tất cả sản phẩm</a>
                        </div>
                    @endforelse

                    {{-- Không tìm thấy sp --}}
                    <div class="empty-state-filter" style="display:none;" id="empty-filter">
                        <i class="bx bx-filter-alt"></i>
                        <p>Không có sản phẩm phù hợp với bộ lọc.</p>
                        <button class="mb-browse-btn" onclick="resetFilters()">Xoá bộ lọc</button>
                    </div>
                </div>

                {{-- Phân trang --}}
                @if ($products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->lastPage() > 1)
                    <div class="pagination-premium">
                        @if ($products->onFirstPage())
                            <span class="page-link-premium disabled"><i class="bx bx-chevron-left"></i></span>
                        @else
                            <a href="{{ $products->previousPageUrl() }}" class="page-link-premium" rel="prev"><i
                                    class="bx bx-chevron-left"></i></a>
                        @endif

                        @php
                            $start = max($products->currentPage() - 2, 1);
                            $end = min($start + 4, $products->lastPage());
                            if ($end === $products->lastPage()) {
                                $start = max($end - 4, 1);
                            }
                        @endphp

                        @if ($start > 1)
                            <a href="{{ $products->url(1) }}" class="page-link-premium">1</a>
                            @if ($start > 2)
                                <span class="page-link-premium dots">...</span>
                            @endif
                        @endif

                        @for ($i = $start; $i <= $end; $i++)
                            @if ($i == $products->currentPage())
                                <span class="page-link-premium active">{{ $i }}</span>
                            @else
                                <a href="{{ $products->url($i) }}" class="page-link-premium">{{ $i }}</a>
                            @endif
                        @endfor

                        @if ($end < $products->lastPage())
                            @if ($end < $products->lastPage() - 1)
                                <span class="page-link-premium dots">...</span>
                            @endif
                            <a href="{{ $products->url($products->lastPage()) }}"
                                class="page-link-premium">{{ $products->lastPage() }}</a>
                        @endif

                        @if ($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" class="page-link-premium" rel="next"><i
                                    class="bx bx-chevron-right"></i></a>
                        @else
                            <span class="page-link-premium disabled"><i class="bx bx-chevron-right"></i></span>
                        @endif
                    </div>
                @endif

            </section>
        </main>
    </div>

    {{-- Scripts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /* Gọi các biến */
            const items = document.querySelectorAll('.product-item');
            const emptyFilter = document.getElementById('empty-filter');
            const countEl = document.getElementById('visible-count');
            const grid = document.getElementById('products-filter');
            const sizeBtns = document.querySelectorAll('#size-filter .mp-size-btn');

            let maxPrice = 5000000;
            let activeSize = null;

            /* Đếm số lượng */
            function updateCount() {
                let n = 0;
                items.forEach(i => {
                    if (i.style.display !== 'none') n++;
                });
                if (countEl) countEl.textContent = n;
                if (emptyFilter) emptyFilter.style.display = n === 0 ? 'block' : 'none';
            }

            /* Lọc sản phẩm */
            function applyFilters() {
                const term = document.getElementById('brand-search')?.value.toLowerCase() || '';
                items.forEach(item => {
                    const name = item.dataset.name || '';
                    const brand = item.dataset.brand || '';
                    const price = parseInt(item.dataset.price) || 0;
                    const sizes = (item.dataset.sizes || '').split(',');

                    const nameOk = !term || name.includes(term) || brand.includes(term);
                    const priceOk = price <= maxPrice;
                    const sizeOk = !activeSize || sizes.includes(activeSize);

                    item.style.display = (nameOk && priceOk && sizeOk) ? '' : 'none';
                });
                updateCount();
            }

            /* Tìm kiếm */
            document.getElementById('brand-search')?.addEventListener('input', applyFilters);

            /* Thanh trượt giá */
            const slider = document.getElementById('price-slider-max');
            const lblMax = document.getElementById('price-label-max');
            slider?.addEventListener('input', function() {
                lblMax.textContent = parseInt(this.value).toLocaleString('vi-VN') + ' ₫';
            });
            document.getElementById('apply-price')?.addEventListener('click', function() {
                maxPrice = parseInt(slider.value);
                applyFilters();
            });

            /* Lọc size */
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

            /* Sắp xếp */
            document.getElementById('sort-select')?.addEventListener('change', function() {
                const val = this.value;
                const arr = Array.from(items);
                arr.sort((a, b) => {
                    if (val === 'price-asc') return parseInt(a.dataset.price) - parseInt(b.dataset
                        .price);
                    if (val === 'price-desc') return parseInt(b.dataset.price) - parseInt(a.dataset
                        .price);
                    if (val === 'name-asc') return (a.dataset.name || '').localeCompare(b.dataset
                        .name || '');
                    return 0;
                });
                arr.forEach(el => grid.appendChild(el));
            });

            /* Chuyển đổi giao diện */
            document.getElementById('view-grid')?.addEventListener('click', function() {
                grid.classList.remove('list-view');
                document.getElementById('view-list')?.classList.remove('active');
                this.classList.add('active');
            });
            document.getElementById('view-list')?.addEventListener('click', function() {
                grid.classList.add('list-view');
                document.getElementById('view-grid')?.classList.remove('active');
                this.classList.add('active');
            });

            /* Xóa bộ lọc */
            window.resetFilters = function() {
                maxPrice = 5000000;
                activeSize = null;
                if (slider) {
                    slider.value = 5000000;
                    lblMax.textContent = '5.000.000 ₫';
                }
                sizeBtns.forEach(b => b.classList.remove('active'));
                if (document.getElementById('brand-search')) document.getElementById('brand-search').value = '';
                document.getElementById('sort-select').value = 'default';
                items.forEach(i => i.style.display = '');
                updateCount();
            };

            /* Wishlist */
            window.toggleWishlist = function(btn, id) {
                const icon = btn.querySelector('i');
                icon.classList.toggle('bx-heart');
                icon.classList.toggle('bxs-heart');
                icon.classList.contains('bxs-heart') ?
                    (icon.style.color = '#e74c3c', btn.setAttribute('title', 'Bỏ yêu thích')) :
                    (icon.style.color = '', btn.setAttribute('title', 'Yêu thích'));
            };

            updateCount();
        });
    </script>

@endsection

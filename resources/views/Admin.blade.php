<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Luma Shoes Admin</title>
    <link rel="stylesheet" href="{{ asset('Admin.css') }}">
    <link rel="website icon" href="{{ asset('img/Logo.png') }}">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>

<body>

    <!-- Mobile Toggle -->
    <div class="menu-toggle" id="menuToggle"
        style="position: fixed; top: 20px; left: 20px; z-index: 2000; background: #000; color: #fff; width: 40px; height: 40px; border-radius: 8px; display: none; align-items: center; justify-content: center; cursor: pointer;">
        <i class="fa fa-bars"></i>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('img/Logo.png') }}" alt="Logo">
            <h3>Luma Shoes</h3>
        </div>

        <ul class="sidebar-menu">
            <li data-section="dashboard" class="active">
                <i class="bx bx-grid-alt"></i>
                <span>Tổng quan</span>
            </li>
            <li data-section="revenue">
                <i class="bx bx-stats"></i>
                <span>Thống kê doanh thu</span>
            </li>
            <li data-section="products">
                <i class="bx bx-package"></i>
                <span>Sản phẩm</span>
            </li>
            <li data-section="brands">
                <i class="bx bx-store"></i>
                <span>Thương hiệu</span>
            </li>
            <li data-section="users">
                <i class="bx bx-group"></i>
                <span>Người dùng</span>
            </li>
            <li data-section="orders">
                <i class="bx bx-cart-alt"></i>
                <span>Đơn hàng</span>
            </li>
            <li data-section="settings">
                <i class="bx bx-cog"></i>
                <span>Cài đặt hệ thống</span>
            </li>
        </ul>

        <div class="sidebar-footer">
            <ul>
                <li id="user-icon">
                    <i class='bx bxs-user-circle' style="font-size: 1.5rem;"></i>
                    <div style="flex-grow: 1;">
                        <p id="userInfo" style="font-size: 0.85rem; font-weight: 700; color: #fff; margin:0;"></p>
                        <p style="font-size: 0.7rem; color: #666; margin:0;">Quản trị viên</p>
                    </div>
                    <i class='bx bx-chevron-up'></i>

                    <!-- User Dropdown -->
                    <ul id="userDropdown">
                        <li><a href="{{ route('home') }}"
                                style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 8px;"><i
                                    class="bx bx-home"></i> Trang chủ shop</a></li>
                        <li id="logoutBtn"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                class="bx bx-log-out"></i> Đăng xuất</li>
                    </ul>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">

        <!-- Header Dynamic -->
        <header class="main-header">
            <div class="page-title">
                <h2 id="current-section-title">Tổng quan hệ thống</h2>
                <p id="current-section-subtitle">Theo dõi hoạt động kinh doanh của Luma Shoes</p>
            </div>
            <div class="header-actions">
                <div class="notification-container">
                    <i class="bx bx-bell" id="bell-icon" style="font-size:20px;"></i>
                    <span id="notificationBadge">0</span>
                    <ul id="notificationList"></ul>
                </div>
                <div style="width: 1px; height: 30px; background: #ddd; margin: 0 10px;"></div>
                <button class="p-btn p-btn-outline" onclick="window.location.reload()">
                    <i class="bx bx-refresh"></i> Làm mới
                </button>
            </div>
        </header>

        <!-- DASHBOARD SECTION -->
        <section id="dashboard" class="section active">
            <!-- Stats Blocks -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-title">Doanh thu</div>
                        <div class="stat-icon icon-income"><i class="bx bx-trending-up"></i></div>
                    </div>
                    <div class="stat-value" id="totalRevenue">0₫</div>
                    <div class="stat-trend trend-up"><i class="bx bx-up-arrow-alt"></i> +12% so với tháng trước</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-title">Đơn hàng</div>
                        <div class="stat-icon icon-orders"><i class="bx bx-shopping-bag"></i></div>
                    </div>
                    <div class="stat-value" id="totalOrders">0</div>
                    <div class="stat-trend trend-up"><i class="bx bx-up-arrow-alt"></i> +5 đơn hàng mới</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-title">Sản phẩm</div>
                        <div class="stat-icon icon-products"><i class="bx bx-package"></i></div>
                    </div>
                    <div class="stat-value" id="totalProducts">0</div>
                    <div class="stat-trend">Tổng sản phẩm hiện có</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-title">Khách hàng</div>
                        <div class="stat-icon icon-users"><i class="bx bx-user"></i></div>
                    </div>
                    <div class="stat-value" id="totalUsersCount">0</div>
                    <div class="stat-trend">Lượng người dùng đã đăng ký</div>
                </div>
            </div>

            <div class="dashboard-grid">
                <!-- Main Chart -->
                <div class="card-panel">
                    <h3><i class='bx bx-bar-chart-square'></i> Xu hướng doanh thu</h3>
                    <div style="height: 350px;">
                        <canvas id="unifiedChart"></canvas>
                    </div>
                </div>

                <!-- Quick Reports Footer -->
                <div class="card-panel">
                    <h3><i class='bx bx-history'></i> Hoạt động gần đây</h3>
                    <div id="recentActivityList">
                        @forelse($activities as $activity)
                            <div class="activity-item">
                                <div class="activity-icon {{ $activity['class'] }}"><i
                                        class='bx {{ $activity['icon'] }}'></i></div>
                                <div class="activity-info">
                                    <p>{!! $activity['content'] !!}</p>
                                    <span class="activity-time">{{ $activity['time']->diffForHumans() }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted" style="font-size: 0.9rem; text-align:center; padding-top: 50px;">
                                Chưa có hoạt động nào mới.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Top Selling / Recent Items -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                <div class="card-panel">
                    <h3>Sản phẩm bán chạy nhất</h3>
                    <div id="productsBreakdown">
                        <!-- JS Rendered -->
                    </div>
                </div>
                <div class="card-panel">
                    <h3>Trạng thái đơn hàng</h3>
                    <div id="ordersBreakdown">
                        <!-- JS Rendered -->
                    </div>
                </div>
            </div>
        </section>

        <!-- REVENUE SECTION -->
        <section id="revenue" class="section">
            <div class="card-panel-full">
                <div class="stats-grid"
                    style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 25px;">
                    <div class="stat-card">
                        <div class="stat-title">Thực thu (Hoàn thành)</div>
                        <div class="stat-value" id="realRevenueDetail" style="color: #000;">0₫</div>
                        <div class="stat-trend" style="color: #10b981;">Đã ghi nhận vào kho</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-title">Dòng tiền chờ (Đang xử lý)</div>
                        <div class="stat-value" id="pendingRevenueDetail" style="color: #666;">0₫</div>
                        <div class="stat-trend" style="color: #f59e0b;">Chưa hoàn tất thanh toán</div>
                    </div>
                </div>

                <div class="dashboard-grid"
                    style="grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 25px;">
                    <!-- Doanh thu cột dọc -->
                    <div class="card-panel" style="margin-bottom: 0;">
                        <h4><i class='bx bx-bar-chart-alt-2'></i> Doanh thu theo ngày</h4>
                        <div style="height: 350px;">
                            <canvas id="revenueBarChart"></canvas>
                        </div>
                    </div>
                    <!-- Thanh toán biểu đồ tròn -->
                    <div class="card-panel" style="margin-bottom: 0;">
                        <h4><i class='bx bx-pie-chart-alt-2'></i> Tỷ lệ thanh toán</h4>
                        <div style="height: 350px; display: flex; align-items: center; justify-content: center;">
                            <canvas id="paymentPieChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="dashboard-grid"
                    style="grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 25px; margin-top: 25px;">
                    <!-- Mẫu giày bán chạy cột ngang -->
                    <div class="card-panel" style="margin-bottom: 0;">
                        <h4><i class='bx bx-purchase-tag'></i> Top mẫu giày bán chạy</h4>
                        <div style="height: 300px;">
                            <canvas id="topShoesChart"></canvas>
                        </div>
                    </div>
                    <!-- Thương hiệu bán chạy cột ngang -->
                    <div class="card-panel" style="margin-bottom: 0;">
                        <h4><i class='bx bx-store'></i> Hiệu suất thương hiệu</h4>
                        <div style="height: 300px;">
                            <canvas id="topBrandsChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="card-panel" style="margin-top: 25px;">
                    <h3><i class='bx bx-user-voice'></i> Xếp hạng khách hàng thân thiết</h3>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Khách hàng</th>
                                    <th style="text-align:center;">Số đơn đã mua</th>
                                    <th style="text-align:center;">Tổng chi tiêu</th>
                                    <th style="text-align:center;">Trạng thái VIP</th>
                                </tr>
                            </thead>
                            <tbody id="topCustomersTable">
                                <!-- JS Rendered -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- PRODUCTS SECTION -->
        <section id="products" class="section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <h3>Danh sách sản phẩm</h3>
                <button id="toggleProductFormBtn" class="p-btn p-btn-dark">
                    <i class="bx bx-plus"></i> Thêm sản phẩm mới
                </button>
            </div>

            <div class="table-responsive card-panel">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Thương hiệu</th>
                            <th>Giá bán</th>
                            <th>Phân loại</th>
                            <th>Sizes / Kho</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="productTable">
                        <!-- JS Loaded -->
                    </tbody>
                </table>
            </div>
        </section>

        <!-- BRANDS SECTION -->
        <section id="brands" class="section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <h3>Quản lý thương hiệu</h3>
                <button id="toggleBrandFormBtn" class="p-btn p-btn-dark">
                    <i class="bx bx-plus"></i> Thêm hãng mới
                </button>
            </div>

            <div class="table-responsive card-panel">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Logo</th>
                            <th>Tên thương hiệu</th>
                            <th>Số sản phẩm</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="brandTable"></tbody>
                </table>
            </div>
        </section>

        <!-- USERS SECTION -->
        <section id="users" class="section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <h3>Danh sách người dùng</h3>
                <button id="toggleUserFormBtn" class="p-btn p-btn-dark">
                    <i class="bx bx-user-plus"></i> Tạo tài khoản
                </button>
            </div>

            <div class="table-responsive card-panel">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tên người dùng</th>
                            <th>Email liên hệ</th>
                            <th>Địa chỉ</th>
                            <th>Đơn hàng mua</th>
                            <th>Vai trò</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="userTable"></tbody>
                </table>
            </div>
        </section>

        <!-- ORDERS SECTION -->
        <section id="orders" class="section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <h3>Quản lý đơn hàng</h3>
                <button class="p-btn p-btn-outline" id="exportExcelBtn">
                    <i class="bx bx-download"></i> Xuất Excel
                </button>
            </div>

            <div class="table-responsive card-panel">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mã ĐH</th>
                            <th>Khách hàng</th>
                            <th>Giá trị</th>
                            <th>Trạng thái</th>
                            <th>Thanh toán</th>
                            <th>Thời gian</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="orderTable"></tbody>
                </table>
            </div>
        </section>

        <!-- SETTINGS SECTION -->
        <section id="settings" class="section">
            <div class="dashboard-grid">
                <div class="card-panel">
                    <h3>Bảo mật tài khoản</h3>
                    <p class="text-muted mb-4">Cập nhật mật khẩu quản trị của bạn thường xuyên để bảo đảm an toàn hệ
                        thống.</p>
                    <button type="button" id="togglePasswordBtn" class="p-btn p-btn-dark">
                        <i class='bx bx-lock-open-alt'></i> Thay mật khẩu mới
                    </button>
                    <form id="passwordForm" style="display: none; margin-top: 25px; animation: fadeIn 0.4s ease-out;">
                        <div class="form-group mb-3">
                            <label>Mật khẩu cũ</label>
                            <input type="password" name="currentPassword" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Mật khẩu mới</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group mb-4">
                            <label>Xác nhận lại</label>
                            <input type="password" name="confirmPassword" class="form-control" required>
                        </div>
                        <button type="submit" class="p-btn p-btn-dark">Cập nhật mật khẩu</button>
                    </form>
                </div>

                <div class="card-panel" style="border: 1px solid var(--danger);">
                    <h3 style="color: var(--danger);"><i class='bx bx-error'></i> Khu vực nguy hiểm</h3>
                    <p class="text-muted mb-4">Các thao tác này sẽ xóa vĩnh viễn dữ liệu trên hệ thống. Hãy cực kỳ cẩn
                        thận!</p>
                    <button id="clearDataBtn" class="p-btn p-btn-outline"
                        style="color: var(--danger); border-color: var(--danger); width: 100%;">
                        XÓA TẤT CẢ DỮ LIỆU CỬA HÀNG
                    </button>
                </div>
            </div>
        </section>

    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="{{ asset('JS/Admin.js') }}"></script>

    <!-- Toast Container -->
    <div id="toast-container"></div>

    <!-- Custom Confirm Modal -->
    <div id="confirm-overlay">
        <div class="confirm-card">
            <div class="confirm-icon"><i class="bx bx-error-circle"></i></div>
            <h3 class="confirm-title" id="confirm-title">Xác nhận</h3>
            <p class="confirm-msg" id="confirm-msg">Bạn có chắc chắn muốn thực hiện thao tác này?</p>
            <div class="confirm-btns">
                <button class="p-btn p-btn-outline" id="confirm-cancel">Hủy bỏ</button>
                <button class="p-btn p-btn-danger" id="confirm-ok">Xác nhận</button>
            </div>
        </div>
    </div>

    <!-- size -->
    <div id="sizeModalOverlay"
        style="
        display:none; position:fixed; inset:0; background:rgba(0,0,0,.55);
        z-index:9000; align-items:center; justify-content:center;
        backdrop-filter:blur(4px); transition:opacity .3s;">
        <div class="card-panel"
            style="
            width:min(680px,95vw); max-height:85vh; overflow-y:auto; padding:28px; position:relative;">

            <button onclick="closeSizeModal()"
                style="
                position:absolute; top:16px; right:16px; background:none;
                border:none; font-size:1.4rem; cursor:pointer; color:#555;">
                <i class='bx bx-x'></i>
            </button>

            <h3 id="sizeModalTitle" style="margin-bottom:6px; font-size:1.1rem;">Quản lý Size</h3>
            <p id="sizeModalProductName" style="font-size:.8rem; color:#888; margin-bottom:20px;"></p>

            <!-- Bảng sizes hiện có -->
            <div class="table-responsive" style="border:1px solid #eee; margin-bottom:20px;">
                <table class="table" style="margin:0;">
                    <thead>
                        <tr>
                            <th>Size</th>
                            <th>Tồn kho</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="sizeTableBody">
                        <!-- JS rendered -->
                    </tbody>
                </table>
            </div>

            <!-- Form thêm size mới -->
            <div style="border-top:1px solid #eee; padding-top:18px; margin-top:4px;">
                <h4 style="font-size:.9rem; font-weight:700; margin-bottom:12px;">
                    <i class='bx bx-plus-circle' style="color:#10b981;"></i> Thêm size mới
                </h4>
                <div style="display:grid; grid-template-columns:1fr 1fr auto; gap:12px; align-items:flex-end;">
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Size *</label>
                        <input type="text" class="form-control" id="newSizeValue" placeholder="VD: 40, 41..."
                            maxlength="10">
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Số lượng *</label>
                        <input type="number" class="form-control" id="newSizeStock" placeholder="0"
                            min="0">
                    </div>
                    <button class="p-btn p-btn-dark" onclick="addSize()" style="height:27px">
                        <i class='bx bx-plus'></i> Thêm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ORDER DETAILS MODAL -->
    <div id="orderDetailOverlay"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.6); z-index:10000; align-items:center; justify-content:center; backdrop-filter:blur(5px); transition:opacity .3s;">
        <div class="card-panel"
            style="width:min(800px,95vw); max-height:95vh; overflow-y:auto; padding:25px; border:none; box-shadow:0 25px 60px rgba(0,0,0,0.4); position:relative;">
            <button onclick="closeOrderDetail()"
                style="position:absolute; top:15px; right:15px; background:#f3f4f6; border:none; width:30px; height:30px; font-size:1.1rem; cursor:pointer; color:#555; display:flex; align-items:center; justify-content:center;"><i
                    class='bx bx-x'></i></button>

            <div
                style="display:flex; align-items:center; gap:12px; margin-bottom:20px; border-bottom:1px solid #000; padding-bottom:15px;">
                <div
                    style="width:40px; height:40px; background:#000; color:#fff; display:flex; align-items:center; justify-content:center; font-size:1.2rem;">
                    <i class='bx bx-receipt'></i>
                </div>
                <div>
                    <h3 style="margin:0; font-size:1.1rem; font-weight:800; text-transform:uppercase;">Đơn hàng <span
                            id="detailOrderCode" style="color:#b00000;">---</span></h3>
                    <p style="margin:0; font-size:0.7rem; color:#888;">ID: #<span id="detailOrderId">---</span> |
                        <span id="detailOrderTime">--/--/----</span>
                    </p>
                </div>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
                <!-- Customer Info -->
                <div style="background:#fcfcfc; padding:15px; border:1px solid #eee;">
                    <h4
                        style="margin-top:0; margin-bottom:12px; font-size:0.85rem; border-bottom:1px solid #eee; padding-bottom:8px; text-transform:uppercase;">
                        <i class='bx bx-user'></i> Khách hàng
                    </h4>
                    <div style="display:flex; flex-direction:column; gap:6px; font-size:0.8rem;">
                        <div><span style="color:#888; width:80px; display:inline-block;">Họ tên:</span> <strong
                                id="detailCustomerName">---</strong></div>
                        <div><span style="color:#888; width:80px; display:inline-block;">Điện thoại:</span> <strong
                                id="detailCustomerPhone">---</strong></div>
                        <div><span style="color:#888; width:80px; display:inline-block;">Địa chỉ:</span> <strong
                                id="detailCustomerAddress">---</strong></div>
                        <div><span style="color:#888; width:80px; display:inline-block;">Ghi chú:</span> <span
                                id="detailOrderNote" style="font-style:italic; color:#999;">---</span></div>
                    </div>
                </div>

                <!-- Payment Info -->
                <div style="background:#fcfcfc; padding:15px; border:1px solid #eee;">
                    <h4
                        style="margin-top:0; margin-bottom:12px; font-size:0.85rem; border-bottom:1px solid #eee; padding-bottom:8px; text-transform:uppercase;">
                        <i class='bx bx-credit-card'></i> Thanh toán
                    </h4>
                    <div style="display:flex; flex-direction:column; gap:6px; font-size:0.8rem;">
                        <div><span style="color:#888; width:80px; display:inline-block;">Phương thức:</span> <span
                                id="detailPaymentMethod" class="p-badge p-badge-dark">---</span></div>
                        <div id="transferInfoBox"
                            style="display:none; background:#fff; padding:8px; border:1px dashed #b00000; margin-top:4px;">
                            <div id="detailTransactionCode"
                                style="font-family:monospace; font-size:1rem; text-align:center; font-weight:900;">---
                            </div>
                        </div>
                        <div><span style="color:#888; width:80px; display:inline-block;">Trạng thái:</span> <span
                                id="detailOrderStatus" class="p-badge">---</span></div>
                        <div><span style="color:#888; width:80px; display:inline-block;">Tổng cộng:</span> <strong
                                id="detailOrderTotal" style="font-size:1rem; color:#b00000;">0₫</strong></div>
                    </div>
                </div>
            </div>

            <!-- Items List -->
            <div>
                <h4 style="margin-bottom:12px; font-size:0.9rem;"><i class='bx bx-package' style="color:#000;"></i>
                    Danh sách sản phẩm</h4>
                <div class="table-responsive" style="border:1px solid #eee;">
                    <table class="table" style="margin:0;">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Size</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-right">Đơn giá</th>
                                <th class="text-right">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody id="detailOrderItems"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- UNIFIED ADD MODAL -->
    <div id="addFormOverlay"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.65); z-index:10000; align-items:center; justify-content:center; backdrop-filter:blur(5px);">
        <div
            style="background:#fff; width:min(640px,95vw); max-height:92vh; overflow-y:auto; position:relative; border: 1px solid #000; font-size:0.82rem;">
            <!-- Header -->
            <div
                style="display:flex; align-items:center; justify-content:space-between; padding:15px 20px; border-bottom:1px solid #000; background:#f9fafb;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div
                        style="width:32px; height:32px; background:#000; color:#fff; display:flex; align-items:center; justify-content:center; font-size:1rem;">
                        <i class='bx bx-plus' id="addModalIcon"></i>
                    </div>
                    <span id="addModalTitle"
                        style="font-size:0.9rem; font-weight:800; text-transform:uppercase; letter-spacing:.5px; position:relative; top:2px;">Thêm
                        mới</span>
                </div>
                <button type="button" onclick="document.getElementById('addFormOverlay').style.display='none'"
                    style="background:none; border:1px solid #ddd; width:28px; height:28px; cursor:pointer; font-size:1rem; display:flex; align-items:center; justify-content:center;">
                    <i class='bx bx-x'></i>
                </button>
            </div>
            <!-- Body -->
            <div style="padding:20px;">
                <!-- Product Form -->
                <form id="productEntryForm" style="display:none;">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-bottom:15px;">
                        <div class="form-group" style="grid-column: span 2; margin-bottom:0;">
                            <label class="form-label">Tên sản phẩm *</label>
                            <input type="text" class="form-control" id="productName"
                                placeholder="Ví dụ: Nike Air Force 1" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Thương hiệu *</label>
                            <select class="form-select" id="productCategory" required></select>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Giới tính *</label>
                            <select class="form-select" id="productGender">
                                <option value="Men">Nam</option>
                                <option value="Woman">Nữ</option>
                                <option value="Unisex">Unisex</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Giá bán (VNĐ) *</label>
                            <input type="text" class="form-control" id="productPrice" placeholder="Mức giá"
                                oninput="formatPriceInput(this)" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Hình ảnh sản phẩm</label>
                            <input type="file" class="form-control" id="productImage" accept="image/*"
                                onchange="previewImage(this, 'productImagePreview')">
                        </div>
                        <div class="form-group" style="grid-column: span 2; margin-bottom:0;">
                            <div id="productImagePreview" style="margin-top:6px;"></div>
                        </div>
                        <div class="form-group" style="grid-column: span 2; margin-bottom:0;">
                            <label class="form-label">Mô tả ngắn</label>
                            <textarea class="form-control" id="productDescription" rows="3" placeholder="Nhập mô tả sản phẩm..."></textarea>
                        </div>
                    </div>
                    <div style="display:flex; gap:10px; padding-top:15px; border-top:1px solid #eee;">
                        <button type="submit" class="p-btn p-btn-dark" style="flex:1; margin-top:0;">Lưu sản
                            phẩm</button>
                        <button type="button" class="p-btn p-btn-outline" style="flex:1;"
                            onclick="document.getElementById('addFormOverlay').style.display='none'">Hủy</button>
                    </div>
                </form>

                <!-- Brand Form -->
                <form id="brandForm" style="display:none;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom:15px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Tên hãng *</label>
                            <input type="text" class="form-control" id="brandName" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Logo đại diện</label>
                            <input type="file" class="form-control" id="brandLogo" accept="image/*"
                                onchange="previewImage(this, 'brandLogoPreview')">
                        </div>
                        <div class="form-group" style="grid-column: span 2; margin-bottom:0;">
                            <div id="brandLogoPreview" style="margin-top:6px;"></div>
                        </div>
                    </div>
                    <div style="display:flex; gap:10px; padding-top:15px; border-top:1px solid #eee;">
                        <button type="submit" class="p-btn p-btn-dark" style="flex:1; margin-top:0;">Lưu thông
                            tin</button>
                        <button type="button" class="p-btn p-btn-outline" style="flex:1;"
                            onclick="document.getElementById('addFormOverlay').style.display='none'">Hủy</button>
                    </div>
                </form>

                <!-- User Form -->
                <form id="userForm" style="display:none;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom:15px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Tên đầy đủ *</label>
                            <input type="text" class="form-control" id="userName" placeholder="Tên đầy đủ"
                                required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" id="userEmail" placeholder="Email" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Vai trò *</label>
                            <select class="form-select" id="userRole">
                                <option value="user">Người dùng</option>
                                <option value="admin">Quản trị viên</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Mật khẩu *</label>
                            <input type="password" class="form-control" id="userPassword" placeholder="Mật khẩu"
                                required>
                        </div>
                        <div class="form-group" style="grid-column: span 2; margin-bottom:0;">
                            <label class="form-label">Địa chỉ</label>
                            <textarea class="form-control" id="userAddress" rows="2" style="resize:vertical;"
                                placeholder="Địa chỉ giao hàng"></textarea>
                        </div>
                    </div>
                    <div style="display:flex; gap:10px; padding-top:15px; border-top:1px solid #eee;">
                        <button type="submit" class="p-btn p-btn-dark" style="flex:1; margin-top:0;">Tạo người
                            dùng</button>
                        <button type="button" class="p-btn p-btn-outline" style="flex:1;"
                            onclick="document.getElementById('addFormOverlay').style.display='none'">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>

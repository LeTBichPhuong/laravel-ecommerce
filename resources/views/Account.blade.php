@extends('Home')

@section('title', 'Tài khoản của tôi - Luma Shoes')

@section('css')
    <link rel="stylesheet" href="{{ asset('Account.css') }}">
@endsection

@section('main_content')
    <main class="studio-account-container">
        <div class="studio-account-header">
            <h1>Tài Khoản</h1>
            <div class="breadcrumb">
                <a href="{{ route('home') }}">TRANG CHỦ</a> / TÀI KHOẢN
            </div>
        </div>

        <div class="studio-account-layout">
            <aside class="studio-sidebar-wrapper">
                <div class="user-profile-summary">
                    <div class="avatar"><i class="bx bx-user"></i></div>
                    <div class="name">{{ Auth::check() ? Auth::user()->username : 'Khách' }}</div>
                </div>
                <div class="account-sidebar">
                    <ul>
                        <li><a href="#" data-section="info" class="active"><i class="bx bx-id-card"></i> Hồ sơ</a>
                        </li>
                        <li><a href="#" data-section="orders"><i class="bx bx-receipt"></i> Đơn hàng</a></li>
                        <li><a href="#" data-section="address"><i class="bx bx-map"></i> Địa chỉ</a></li>
                        <li><a href="#" data-section="downloads"><i class="bx bx-download"></i> Tải xuống</a></li>
                        <li><a href="#" data-section="logout"><i class="bx bx-log-out"></i> Đăng xuất</a></li>
                    </ul>
                </div>
            </aside>

            <section class="studio-account-main">
                <!-- Đơn hàng -->
                <div id="orders" class="content-section" style="display:none;">
                    <h2 class="section-title">Lịch Sử Đơn Hàng</h2>

                    @if ($orders->isEmpty())
                        <div class="studio-empty-order">
                            <i class="bx bx-shopping-bag"></i>
                            <p>Bạn chưa có đơn hàng nào.</p>
                            <a href="{{ route('products.list') }}" class="studio-btn"><i class="bx bx-cart"></i> Mua sắm
                                ngay</a>
                        </div>
                    @else
                        @foreach ($orders as $order)
                            <div class="studio-order-card" data-status="{{ $order->status }}">
                                <div class="studio-order-header">
                                    <div class="order-id-box">
                                        <h3>Đơn hàng <span>{{ $order->order_code }}</span></h3>
                                        <p class="order-date"><i class="bx bx-calendar"></i>
                                            {{ $order->created_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="order-status-box">
                                        <span class="studio-badge badge-{{ trim(strtolower($order->status)) }}">
                                            @switch($order->status)
                                                @case('pending')
                                                    Chờ Xử Lý
                                                @break

                                                @case('processing')
                                                @case('confirmed')
                                                    Đã Xác Nhận
                                                @break

                                                @case('shipping')
                                                @case('shipped')
                                                    Đang Giao
                                                @break

                                                @case('completed')
                                                @case('delivered')
                                                    Hoàn Thành
                                                @break

                                                @case('cancelled')
                                                    Đã Hủy
                                                @break

                                                @default
                                                    {{ ucfirst($order->status) }}
                                            @endswitch
                                        </span>
                                    </div>
                                </div>

                                @if ($order->items && $order->items->count() > 0)
                                    <div class="studio-order-body">
                                        @foreach ($order->items as $item)
                                            <div class="studio-order-item">
                                                <div class="item-img">
                                                    <img src="{{ asset($item->product_image ?? 'img/placeholder-product.png') }}"
                                                        alt="{{ $item->product_name }}">
                                                </div>
                                                <div class="item-details">
                                                    <h4>{{ $item->product_name }}</h4>
                                                    <p>Size: <span>{{ $item->size ?? 'Mặc định' }}</span></p>
                                                    <p>Số lượng: <span>x{{ $item->quantity }}</span></p>
                                                </div>
                                                <div class="item-price">
                                                    <strong>{{ \App\Helpers\Helpers::format($item->price) }}</strong>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="studio-order-body empty">
                                        <p>Không có sản phẩm trong đơn hàng này.</p>
                                    </div>
                                @endif

                                <div class="studio-order-footer">
                                    <div class="footer-info">
                                        <p>Thanh toán:
                                            <strong>
                                                @if ($order->payment_method == 'COD')
                                                    COD
                                                @elseif($order->payment_method == 'QR')
                                                    QR Code
                                                @else
                                                    {{ $order->payment_method }}
                                                @endif
                                            </strong>
                                        </p>
                                        <p class="total-price">Tổng cộng: <strong
                                                class="highlight-total">{{ \App\Helpers\Helpers::format($order->total) }}</strong>
                                        </p>
                                    </div>
                                    <div class="footer-actions">
                                        @if ($order->status == 'pending')
                                            <button class="studio-btn-danger cancel-order"
                                                data-order-id="{{ $order->id }}">
                                                <i class="bx bx-x"></i> Hủy Đơn
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Tệp tải xuống -->
                <div id="downloads" class="account-section" style="display:none;">
                    <h2 class="section-title">Kho Tệp Kỹ Thuật Số</h2>
                    <div class="studio-download-wrapper">
                        <table class="studio-download-table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Số lần tải còn lại</th>
                                    <th>Ngày hết hạn</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="download-product-cell">
                                            <div class="download-icon-box"><i class='bx bxs-file-pdf'></i></div>
                                            <div class="download-info">
                                                <h4>E-Book: Hướng dẫn bảo quản giày Sneaker</h4>
                                                <span>Định dạng: PDF | 12.5 MB</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Không giới hạn</td>
                                    <td><span class="status-valid">Vĩnh viễn</span></td>
                                    <td>
                                        <a href="#" class="studio-btn-download">
                                            <i class='bx bx-download'></i> Tải về
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="download-product-cell">
                                            <div class="download-icon-box"><i class='bx bxs-coupon'></i></div>
                                            <div class="download-info">
                                                <h4>Voucher ưu đãi 20% cho BST Nike 2026</h4>
                                                <span>Mã: LUMA-SUMMER-20</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>01 lần</td>
                                    <td>31/12/2026</td>
                                    <td>
                                        <a href="#" class="studio-btn-download">
                                            <i class='bx bx-download'></i> Tải về
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Địa chỉ -->
                <div id="address" class="account-section" style="display:none;">
                    <h2 class="section-title">Quản Lý Địa Chỉ</h2>

                    <div class="info-grid">
                        @if (empty($user->address))
                            <div class="info-item" style="grid-column: 1 / -1; text-align: center;">Chưa thiết lập thông tin
                                địa chỉ giao hàng.</div>
                        @else
                            <div class="info-item">
                                <div class="info-label">Địa chỉ</div>
                                <div class="info-val">{{ $user->address }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Thành phố</div>
                                <div class="info-val">{{ $user->city }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Số điện thoại</div>
                                <div class="info-val">{{ $user->phone }}</div>
                            </div>
                        @endif
                    </div>

                    <button type="button" class="studio-btn studio-btn-outline" onclick="toggleForm('addressForm')"><i
                            class="bx bx-edit-alt"></i> Cập nhật địa chỉ</button>

                    <div id="addressForm" class="hidden studio-form-wrapper">
                        <form action="{{ route('account.update.address') }}" method="POST">
                            @csrf
                            <div class="studio-form-group">
                                <label>Địa chỉ cụ thể</label>
                                <input class="studio-input" type="text" name="address"
                                    value="{{ old('address', $user->address) }}" required>
                            </div>
                            <div class="studio-form-group">
                                <label>Tỉnh / Thành phố</label>
                                <input class="studio-input" type="text" name="city"
                                    value="{{ old('city', $user->city) }}" required>
                            </div>
                            <div class="studio-form-group">
                                <label>Số điện thoại</label>
                                <input class="studio-input" type="text" name="phone"
                                    value="{{ old('phone', $user->phone) }}" required>
                            </div>
                            <button type="submit" class="studio-btn"><i class="bx bx-save"></i> Lưu thông tin</button>
                        </form>
                    </div>
                </div>

                <!-- Thông tin tài khoản -->
                <div id="info" class="account-section">
                    <h2 class="section-title">Thông Tin Hồ Sơ</h2>

                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Tên người dùng</div>
                            <div class="info-val">{{ Auth::user()->username }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Email liên hệ</div>
                            <div class="info-val">{{ Auth::user()->email }}</div>
                        </div>
                    </div>

                    <button type="button" class="studio-btn studio-btn-outline" onclick="toggleForm('passwordForm')"><i
                            class="bx bx-lock"></i> Đổi mật khẩu</button>

                    <div id="passwordForm" class="hidden studio-form-wrapper">
                        <form action="{{ route('account.changePassword') }}" method="POST">
                            @csrf
                            <div class="studio-form-group">
                                <label>Mật khẩu hiện tại</label>
                                <input class="studio-input" type="password" name="current_password" required>
                            </div>
                            <div class="studio-form-group">
                                <label>Mật khẩu mới</label>
                                <input class="studio-input" type="password" name="new_password" required>
                            </div>
                            <div class="studio-form-group">
                                <label>Xác nhận mật khẩu</label>
                                <input class="studio-input" type="password" name="new_password_confirmation" required>
                            </div>
                            <button type="submit" class="studio-btn"><i class="bx bx-key"></i> Đổi mật khẩu</button>
                        </form>
                    </div>
                </div>

                <!-- Đăng xuất -->
                <div id="logout" class="account-section" style="display:none;">
                    <h2 class="section-title">Đăng Xuất</h2>
                    <div class="info-item" style="max-width: 400px;">
                        <p style="margin-bottom:20px;">Bạn có chắc chắn muốn đăng xuất khỏi tài khoản?</p>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="studio-btn studio-btn-danger"><i class="bx bx-log-out"></i>
                                Đăng xuất ngay</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection

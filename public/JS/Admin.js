// GLOBAL VARIABLES
let csrfToken = '';
let products = [];
let brands = [];
let users = [];
let orders = [];
let unifiedChart = null;
let revenueBarChart = null;
let paymentPieChart = null;
let topShoesChart = null;
let topBrandsChart = null;
const PLACEHOLDER_IMAGE = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="150" height="150"%3E%3Crect width="150" height="150" fill="%23e0e0e0"/%3E%3Ctext x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-family="Arial, sans-serif" font-size="14" fill="%23999"%3EKHÔNG CÓ ẢNH%3C/text%3E%3C/svg%3E';

const statusMap = {
    'pending': { vi: 'Chờ xử lý', color: '#f59e0b', icon: 'bx-time', class: 'p-badge-warning' },
    'confirmed': { vi: 'Đã xác nhận', color: '#10b981', icon: 'bx-check-double', class: 'p-badge-success' },
    'shipped': { vi: 'Đang giao', color: '#3b82f6', icon: 'bx-truck', class: 'p-badge-info' },
    'delivered': { vi: 'Đã giao', color: '#6366f1', icon: 'bx-package', class: 'p-badge-success' },
    'cancelled': { vi: 'Đã hủy', color: '#ef4444', icon: 'bx-x-circle', class: 'p-badge-danger' },
    'chờ xử lý': { vi: 'Chờ xử lý', color: '#f59e0b', icon: 'bx-time', class: 'p-badge-warning' },
    'đã xác nhận': { vi: 'Đã xác nhận', color: '#10b981', icon: 'bx-check-double', class: 'p-badge-success' },
    'đang giao': { vi: 'Đang giao', color: '#3b82f6', icon: 'bx-truck', class: 'p-badge-info' },
    'đã giao': { vi: 'Đã giao', color: '#6366f1', icon: 'bx-package', class: 'p-badge-success' },
    'đã hủy': { vi: 'Đã hủy', color: '#ef4444', icon: 'bx-x-circle', class: 'p-badge-danger' }
};

// lây csrfToken
function getCsrfToken() {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    return token || '';
}

// giá
function formatCurrency(amount) {
    if (typeof amount === 'string' && amount.includes('₫')) return amount;
    const numAmount = parseFloat(String(amount || '0').replace(/[^\d.]/g, ''));
    if (isNaN(numAmount)) return '0 ₫';
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(numAmount);
}

function formatPriceInput(input) {
    let val = String(input.value).replace(/[^\d]/g, '');
    if (val) {
        input.value = new Intl.NumberFormat('vi-VN').format(parseInt(val, 10));
    } else {
        input.value = '';
    }
}
function sanitizeNumericString(value) {
    const cleaned = String(value || '').replace(/[^\d.]/g, '');
    const parts = cleaned.split('.');
    if (parts.length <= 2) return cleaned;
    return parts[0] + '.' + parts.slice(1).join('');
}

function getImagePath(imagePath) {
    if (!imagePath) return PLACEHOLDER_IMAGE;
    if (imagePath.startsWith('http')) return imagePath;
    if (imagePath.startsWith('/storage/')) return imagePath;
    if (imagePath.startsWith('storage/')) return `/${imagePath}`;
    return `/storage/${imagePath}`;
}

// Web Fetch wrapper
async function webFetch(url, options = {}) {
    const headers = {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        ...(options.headers || {})
    };
    if (options.body && !(options.body instanceof FormData)) {
        headers['Content-Type'] = 'application/json';
    }
    const response = await fetch(url, { ...options, headers, credentials: 'same-origin' });
    if (!response.ok) {
        const text = await response.text();
        throw new Error(text || `HTTP ${response.status}`);
    }
    return response;
}

function getVietnameseStatus(enStatus) {
    return statusMap[enStatus?.toLowerCase()]?.vi || enStatus;
}

function getEnglishStatus(vnStatus) {
    const map = {
        'chờ xử lý': 'pending', 'đã xác nhận': 'confirmed',
        'đang giao': 'shipped', 'đã giao': 'delivered', 'đã hủy': 'cancelled'
    };
    return map[vnStatus?.toLowerCase()] || vnStatus;
}

function getStatusBadgeClass(status) {
    return statusMap[status?.toLowerCase()]?.class || 'p-badge-info';
}

// Thông báo
function showToast(title, message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    
    const icons = { success: 'bx-check-circle', error: 'bx-x-circle', info: 'bx-info-circle' };
    
    toast.innerHTML = `
        <div class="toast-icon"><i class='bx ${icons[type]}'></i></div>
        <div class="toast-content">
            <div class="toast-title">${title}</div>
            <div class="toast-msg">${message}</div>
        </div>
    `;

    container.appendChild(toast);
    setTimeout(() => toast.classList.add('active'), 10);
    
    setTimeout(() => {
        toast.classList.remove('active');
        setTimeout(() => toast.remove(), 500);
    }, 4000);
}

// Xác nhận
function showConfirm(title, message, onConfirm) {
    const overlay = document.getElementById('confirm-overlay');
    const titleEl = document.getElementById('confirm-title');
    const msgEl = document.getElementById('confirm-msg');
    const btnOk = document.getElementById('confirm-ok');
    const btnCancel = document.getElementById('confirm-cancel');

    if (!overlay || !titleEl || !msgEl || !btnOk || !btnCancel) return;

    titleEl.textContent = title;
    msgEl.textContent = message;
    overlay.style.display = 'flex';
    setTimeout(() => overlay.classList.add('active'), 10);

    const close = () => {
        overlay.classList.remove('active');
        setTimeout(() => overlay.style.display = 'none', 300);
    };

    btnOk.onclick = () => { close(); onConfirm(); };
    btnCancel.onclick = close;
    overlay.onclick = (e) => { if (e.target === overlay) close(); };
}

// Xem trước ảnh
function previewImage(input, previewId) {
    const previewContainer = document.getElementById(previewId);
    if (!previewContainer) return;

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewContainer.innerHTML = `<img src="${e.target.result}" style="max-width: 100px; max-height: 100px; border-radius: 8px; object-fit: contain; background: #fff; padding: 3px; border: 1px solid #ddd; margin-top: 10px;">`;
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        previewContainer.innerHTML = '';
    }
}

// Modal chỉnh sửa
function injectEditModal() {
    if (document.getElementById('editFormOverlay')) return;

    const html = `
    <div id="editFormOverlay" style="
        display:none; position:fixed; inset:0;
        background:rgba(0,0,0,.65); z-index:10000;
        align-items:center; justify-content:center;
        backdrop-filter:blur(5px);">

        <div style="
            background:var(--bg-card, #fff);
            width:min(640px,95vw); max-height:92vh;
            overflow-y:auto; position:relative;
            border: 1px solid #000;
            font-size:0.82rem;">

            <!-- Header -->
            <div style="
                display:flex; align-items:center; justify-content:space-between;
                padding:15px 20px; border-bottom:1px solid #000; background:#f9fafb;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div id="editModalIcon" style="
                        width:32px; height:32px; background:#000; color:#fff;
                        display:flex; align-items:center; justify-content:center;
                        font-size:1rem;">
                        <i class='bx bx-edit'></i>
                    </div>
                    <span id="editModalTitle" style="font-size:0.9rem; font-weight:800; text-transform:uppercase; letter-spacing:.5px; position:relative; top:2px;">Chỉnh sửa</span>
                </div>
                <button onclick="closeEditForm()" style="
                    background:none; border:1px solid #ddd;
                    width:28px; height:28px; cursor:pointer; font-size:1rem;
                    display:flex; align-items:center; justify-content:center;">
                    <i class='bx bx-x'></i>
                </button>
            </div>

            <!-- Body -->
            <div style="padding:20px;">

                <!-- PRODUCT FORM -->
                <form id="editProductForm" style="display:none;">
                    <input type="hidden" id="editProductId">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
                        <div class="form-group" style="grid-column:span 2; margin-bottom:0;">
                            <label class="form-label">Tên sản phẩm *</label>
                            <input type="text" class="form-control" id="editProductName" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Thương hiệu *</label>
                            <select class="form-select" id="editProductBrand" required></select>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Giới tính *</label>
                            <select class="form-select" id="editProductGender">
                                <option value="Men">Nam</option>
                                <option value="Woman">Nữ</option>
                                <option value="Unisex">Unisex</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Giá bán (VNĐ) *</label>
                            <input type="text" class="form-control" id="editProductPrice" oninput="formatPriceInput(this)" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Ảnh sản phẩm</label>
                            <input type="file" class="form-control" id="editProductImage" accept="image/*" onchange="previewImage(this,'editProductPreview')">
                        </div>
                        <div class="form-group" style="grid-column:span 2; margin-bottom:0;">
                            <div id="editProductPreview" style="margin-top:6px;"></div>
                        </div>
                        <div class="form-group" style="grid-column:span 2; margin-bottom:0;">
                            <label class="form-label">Mô tả</label>
                            <textarea class="form-control" id="editProductDescription" rows="3" style="resize:vertical;"></textarea>
                        </div>
                    </div>
                    <div style="display:flex; gap:10px; padding-top:15px; border-top:1px solid #eee;">
                        <button type="submit" class="p-btn p-btn-dark" style="flex:1; margin-top:0;">Lưu thay đổi</button>
                        <button type="button" class="p-btn p-btn-outline" onclick="closeEditForm()" style="flex:1;">Hủy</button>
                    </div>
                </form>

                <!-- BRAND FORM -->
                <form id="editBrandForm" style="display:none;">
                    <input type="hidden" id="editBrandId">
                    <div style="display:grid; gap:12px; margin-bottom:12px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Tên thương hiệu *</label>
                            <input type="text" class="form-control" id="editBrandName" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Logo thương hiệu</label>
                            <input type="file" class="form-control" id="editBrandLogo" accept="image/*" onchange="previewImage(this,'editBrandPreview')">
                            <div id="editBrandPreview" style="margin-top:6px;"></div>
                        </div>
                    </div>
                    <div style="display:flex; gap:10px; padding-top:15px; border-top:1px solid #eee;">
                        <button type="submit" class="p-btn p-btn-dark" style="flex:1; margin-top:0;">Cập nhật</button>
                        <button type="button" class="p-btn p-btn-outline" onclick="closeEditForm()" style="flex:1;">Hủy</button>
                    </div>
                </form>

                <!-- USER FORM -->
                <form id="editUserForm" style="display:none;">
                    <input type="hidden" id="editUserId">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Họ tên *</label>
                            <input type="text" class="form-control" id="editUserName" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Tên đăng nhập *</label>
                            <input type="text" class="form-control" id="editUserUsername" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" id="editUserEmail" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Vai trò *</label>
                            <select class="form-select" id="editUserRole">
                                <option value="user">Người dùng</option>
                                <option value="admin">Quản trị viên</option>
                            </select>
                        </div>
                        <div class="form-group" style="grid-column:span 2; margin-bottom:0;">
                            <label class="form-label">Mật khẩu mới <span style="color:#aaa;">(bỏ trống nếu không đổi)</span></label>
                            <input type="password" class="form-control" id="editUserPassword">
                        </div>
                        <div class="form-group" style="grid-column:span 2; margin-bottom:0;">
                            <label class="form-label">Địa chỉ</label>
                            <textarea class="form-control" id="editUserAddress" rows="2" style="resize:none;"></textarea>
                        </div>
                    </div>
                    <div style="display:flex; gap:10px; padding-top:15px; border-top:1px solid #eee;">
                        <button type="submit" class="p-btn p-btn-dark" style="flex:1; margin-top:0;">Lưu thay đổi</button>
                        <button type="button" class="p-btn p-btn-outline" onclick="closeEditForm()" style="flex:1;">Hủy</button>
                    </div>
                </form>

            </div>
        </div>
    </div>`;

    document.body.insertAdjacentHTML('beforeend', html);

    // Close on backdrop click
    document.getElementById('editFormOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeEditForm();
    });

    // Attach submit handlers
    document.getElementById('editProductForm').addEventListener('submit', handleProductEdit);
    document.getElementById('editBrandForm').addEventListener('submit', handleBrandEdit);
    document.getElementById('editUserForm').addEventListener('submit', handleUserEdit);
}

// Hiển thị form chỉnh sửa
function showEditForm(type, id) {
    injectEditModal();

    const forms = ['editProductForm', 'editBrandForm', 'editUserForm'];
    forms.forEach(f => document.getElementById(f).style.display = 'none');

    const overlay = document.getElementById('editFormOverlay');
    overlay.style.display = 'flex';

    const iconEl = document.getElementById('editModalIcon');
    const titleEl = document.getElementById('editModalTitle');

    if (type === 'product') {
        document.getElementById('editProductForm').style.display = 'block';
        iconEl.innerHTML = "<i class='bx bx-package'></i>";
        titleEl.textContent = 'Chỉnh sửa sản phẩm';
        loadProductForEdit(id);
    }
    if (type === 'brand') {
        document.getElementById('editBrandForm').style.display = 'block';
        iconEl.innerHTML = "<i class='bx bx-store'></i>";
        titleEl.textContent = 'Chỉnh sửa thương hiệu';
        loadBrandForEdit(id);
    }
    if (type === 'user') {
        document.getElementById('editUserForm').style.display = 'block';
        iconEl.innerHTML = "<i class='bx bx-user'></i>";
        titleEl.textContent = 'Chỉnh sửa người dùng';
        loadUserForEdit(id);
    }
}

// Đóng modal chỉnh sửa
function closeEditForm() {
    const overlay = document.getElementById('editFormOverlay');
    if (overlay) overlay.style.display = 'none';
}


// Load dữ liệu
async function loadProducts() {
    try {
        const res = await webFetch('/admin/products');
        const data = await res.json();
        products = data.data || data;
        window.products = products;
        renderProducts();
    } catch (e) { 
        console.error('Load products error:', e);
        showToast('Lỗi!', 'Không thể tải danh sách sản phẩm.', 'error');
    }
}

async function loadBrands() {
    try {
        const res = await webFetch('/admin/brands');
        const data = await res.json();
        brands = data.data || data;
        window.brands = brands;
        renderBrands();
        populateBrandSelects();
    } catch (e) { 
        console.error('Load brands error:', e); 
        showToast('Lỗi', 'Không thể tải danh sách thương hiệu.', 'error');
    }
}

async function loadUsers() {
    try {
        const res = await webFetch('/admin/users');
        const data = await res.json();
        users = data.data || data;
        window.users = users;
        renderUsers();
    } catch (e) { 
        console.error('Load users error:', e); 
        showToast('Lỗi', 'Không thể tải người dùng.', 'error');
    }
}

async function loadOrders() {
    try {
        const res = await webFetch('/admin/orders');
        const data = await res.json();
        const rawOrders = data.data || data;
        orders = rawOrders.map(o => ({ ...o, status: getEnglishStatus(o.status) }));
        window.orders = orders;
        renderOrders();
    } catch (e) { 
        console.error('Load orders error:', e); 
        showToast('Lỗi', 'Không thể tải đơn hàng.', 'error');
    }
}

// populate danh sách thương hiệu
function populateBrandSelects() {
    const selects = [document.getElementById('productCategory'), document.getElementById('editProductBrand')];
    selects.forEach(s => {
        if (!s) return;
        s.innerHTML = '<option value="">Chọn hãng</option>' + brands.map(b => `<option value="${b.id}">${b.name}</option>`).join('');
    });
}

// Render danh sách sản phẩm
function renderProducts() {
    const el = document.getElementById('productTable');
    if (!el) return;
    if (products.length === 0) { el.innerHTML = '<tr><td colspan="7" class="text-center py-4">Chưa có sản phẩm.</td></tr>'; return; }
    el.innerHTML = products.map(p => {
        const sizes = p.sizes || [];
        let sizesHtml;
        if (sizes.length === 0) {
            sizesHtml = '<span style="font-size:0.75rem; color:#bbb;">Chưa có size</span>';
        } else {
            const totalStock = sizes.reduce((s, z) => s + (z.stock || 0), 0);
            const chips = sizes.slice(0, 4).map(z => {
                const cls = z.stock === 0
                    ? 'background:#fee2e2;color:#ef4444;'
                    : (z.stock <= 5 ? 'background:#fef3c7;color:#d97706;' : 'background:#f0fdf4;color:#16a34a;');
                return `<span style="${cls}border-radius:6px;padding:2px 7px;font-size:0.7rem;font-weight:700;">${z.size}<sup>${z.stock}</sup></span>`;
            }).join(' ');
            const more = sizes.length > 4 ? `<span style="font-size:0.7rem;color:#888;">+${sizes.length - 4}</span>` : '';
            sizesHtml = `<div style="display:flex;flex-wrap:wrap;gap:4px;align-items:center;">${chips}${more}</div>`
                      + `<div style="font-size:0.7rem;color:#666;margin-top:4px;">Tổng: <b>${totalStock}</b></div>`;
        }
        const escapedName = p.name.replace(/\\/g, '\\\\').replace(/'/g, "\\'");
        return `
        <tr>
            <td><img src="${getImagePath(p.image)}" width="45" height="45" style="object-fit:cover;border-radius:8px;"></td>
            <td><div style="font-weight:700;">${p.name}</div><div style="font-size:0.7rem;color:#888;">ID: ${p.id}</div></td>
            <td><span class="p-badge p-badge-info">${p.brand ? p.brand.name : 'N/A'}</span></td>
            <td style="font-weight:800;">${formatCurrency(p.price)}</td>
            <td><span class="p-badge">${p.gender || 'Unisex'}</span></td>
            <td>${sizesHtml}</td>
            <td>
                <div style="display:flex;gap:5px;flex-wrap:wrap;">
                    <button class="btn-icon" style="background:#10b981;color:#fff;" title="Quản lý Size" onclick="openSizeModal(${p.id},'${escapedName}')"><i class='bx bx-grid-small'></i></button>
                    <button class="btn-icon btn-icon-warning" onclick="showEditForm('product',${p.id})"><i class='bx bx-edit-alt'></i></button>
                    <button class="btn-icon btn-icon-danger" onclick="deleteProduct(${p.id})"><i class='bx bx-trash'></i></button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

// Render danh sách thương hiệu
function renderBrands() {
    const el = document.getElementById('brandTable');
    if (!el) return;
    el.innerHTML = brands.map(b => `
        <tr>
            <td><img src="${getImagePath(b.logo)}" width="50" height="50" style="object-fit:contain; background:#fff; padding:3px; border-radius:5px;"></td>
            <td style="font-weight:700;">${b.name}</td>
            <td><span class="p-badge p-badge-dark">${b.products_count || 0} SP</span></td>
            <td>
                <div style="display:flex; gap:5px;">
                    <button class="btn-icon btn-icon-warning" onclick="showEditForm('brand', ${b.id})"><i class='bx bx-edit-alt'></i></button>
                    <button class="btn-icon btn-icon-danger" onclick="deleteBrand(${b.id})"><i class='bx bx-trash'></i></button>
                </div>
            </td>
        </tr>`).join('');
}

// Render danh sách người dùng
function renderUsers() {
    const el = document.getElementById('userTable');
    if (!el) return;
    el.innerHTML = users.map(u => `
        <tr>
            <td style="font-weight:700;">${u.username || u.name}</td>
            <td style="color:#666;">${u.email}</td>
            <td style="font-size:0.85rem; color:#444; max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="${u.address || 'Chưa cập nhật'}">${u.address || '---'}</td>
            <td><span class="p-badge p-badge-dark">${u.orders_count || 0}</span></td>
            <td><span class="p-badge ${u.role === 'admin' ? 'p-badge-danger' : 'p-badge-info'}">${u.role === 'admin' ? 'Admin' : 'User'}</span></td>
            <td>
                <div style="display:flex; gap:5px;">
                    <button class="btn-icon btn-icon-warning" onclick="showEditForm('user', ${u.id})"><i class='bx bx-edit-alt'></i></button>
                    <button class="btn-icon btn-icon-danger" onclick="deleteUser(${u.id})"><i class='bx bx-trash'></i></button>
                </div>
            </td>
        </tr>`).join('');
}

// Render danh sách đơn hàng
function renderOrders() {
    const el = document.getElementById('orderTable');
    if (!el) return;
    el.innerHTML = orders.map(o => {
        const vnStatus = getVietnameseStatus(o.status);
        const badge = getStatusBadgeClass(o.status);
        return `
            <tr>
                <td style="font-weight:800; color:#b00000;">${o.order_code}</td>
                <td style="font-weight:700;">${o.user ? (o.user.username || o.user.name) : 'Khách'}</td>
                <td style="font-weight:800;">${formatCurrency(o.total)}</td>
                <td><span class="p-badge ${badge}">${vnStatus}</span></td>
                <td><span class="p-badge p-badge-dark">${o.payment_method || 'COD'}</span></td>
                <td style="font-size:0.75rem;">${new Date(o.created_at).toLocaleDateString('vi-VN')}</td>
                <td>
                    <div style="display:flex; gap:5px;">
                        <select onchange="updateOrderStatus(${o.id}, this.value)" class="form-select form-select-sm" style="width:100px; font-size:0.7rem;">
                            <option value="" disabled selected>Đổi TT</option>
                            <option value="chờ xử lý">Chờ xử lý</option>
                            <option value="đã xác nhận">Đã xác nhận</option>
                            <option value="đang giao">Đang giao</option>
                            <option value="đã giao">Đã giao</option>
                            <option value="đã hủy">Đã hủy</option>
                        </select>
                        <button class="btn-icon" style="background:#000; color:#fff;" onclick="showOrderDetails(${o.id})" title="Xem chi tiết"><i class='bx bx-show'></i></button>
                        <button class="btn-icon btn-icon-danger" onclick="deleteOrder(${o.id})"><i class='bx bx-trash'></i></button>
                    </div>
                </td>
            </tr>`;
    }).join('');
}

// Dashboard thống kê
function renderBreakdowns(ordersData, productsData) {
    const ordersEl = document.getElementById('ordersBreakdown');
    const productsEl = document.getElementById('productsBreakdown');
    
    if (ordersEl) {
        const stats = { 'pending': 0, 'confirmed': 0, 'shipped': 0, 'delivered': 0, 'cancelled': 0 };
        ordersData.forEach(o => stats[o.status] = (stats[o.status] || 0) + 1);
        ordersEl.innerHTML = Object.entries(statusMap).filter(([k]) => !k.includes(' ')).map(([key, info]) => {
            const count = stats[key] || 0;
            const pct = ordersData.length > 0 ? (count / ordersData.length * 100).toFixed(0) : 0;
            return `
                <div style="margin-bottom:12px;">
                    <div style="display:flex; justify-content:space-between; font-size:0.8rem; margin-bottom:4px;">
                        <span><i class='bx ${info.icon}' style="color:${info.color}"></i> ${info.vi}</span>
                        <span style="font-weight:800;">${count}</span>
                    </div>
                    <div style="height:6px; background:#eee; border-radius:10px; overflow:hidden;"><div style="height:100%; width:${pct}%; background:${info.color}"></div></div>
                </div>`;
        }).join('');
    }

    if (productsEl) {
        const top = {};
        ordersData.forEach(o => o.items?.forEach(i => {
            const key = i.product_name || `SP #${i.id}`;
            top[key] = (top[key] || 0) + (i.quantity || 1);
        }));
        const sorted = Object.entries(top).sort(([,a],[,b]) => b-a).slice(0, 5);

        if (sorted.length === 0) {
            productsEl.innerHTML = '<p style="text-align:center; color:#999; font-size:0.85rem; padding:20px 0;">Chưa có dữ liệu bán hàng.</p>';
        } else {
            productsEl.innerHTML = sorted.map(([name, qty], i) => `
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px; padding:8px; background:#f9f9f9; border-radius:10px;">
                    <div style="width:24px; height:24px; border-radius:50%; background:#000; color:#fff; display:flex; align-items:center; justify-content:center; font-size:0.7rem; flex-shrink:0;">${i+1}</div>
                    <div style="flex:1; min-width:0;"><div style="font-weight:700; font-size:0.85rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${name}</div><div style="font-size:0.7rem; color:#666;">Đã bán: ${qty}</div></div>
                </div>`).join('');
        }
    }
}

// logic phụ trợ
async function updateOrderStatus(id, status) {
    try {
        const res = await webFetch(`/admin/orders/${id}/update-status`, { method: 'POST', body: JSON.stringify({ status }) });
        const json = await res.json();
        await loadOrders();
        // Nếu có trừ kho → reload products để cập nhật cột Sizes/Kho
        if (json.deducted && json.stock_updates && json.stock_updates.length > 0) {
            await loadProducts();
            showToast('Kho cập nhật', `Đã trừ kho ${json.stock_updates.length} size.`, 'info');
        }
        updateDashboard();
        renderNotifications();
    } catch (e) { alert('Lỗi cập nhật trạng thái: ' + e.message); }
}

// logic xóa đơn hàng
async function deleteOrder(id) {
    showConfirm('Xác nhận xóa', 'Bạn có chắc chắn muốn xóa đơn hàng này? Thao tác này không thể hoàn tác.', async () => {
        try { 
            await webFetch(`/admin/orders/${id}`, { method: 'DELETE' }); 
            await loadOrders(); 
            updateDashboard(); 
            renderNotifications();
            showToast('Thành công', 'Đã xóa đơn hàng thành công.');
        } catch (e) { 
            showToast('Lỗi', 'Không thể xóa đơn hàng.', 'error');
        }
    });
}

// logic xem chi tiết đơn hàng
function showOrderDetails(id) {
    const o = orders.find(x => x.id == id);
    if (!o) return;

    document.getElementById('detailOrderCode').textContent = o.order_code;
    document.getElementById('detailOrderId').textContent = o.id;
    document.getElementById('detailOrderTime').textContent = 'Ngày đặt: ' + new Date(o.created_at).toLocaleString('vi-VN');
    
    // Thông tin khách hàng (Lấy từ bảng orders mới update hoặc từ user nếu cũ)
    document.getElementById('detailCustomerName').textContent = o.customer_name || (o.user ? (o.user.name || o.user.username) : 'N/A');
    document.getElementById('detailCustomerPhone').textContent = o.customer_phone || '---';
    document.getElementById('detailCustomerAddress').textContent = o.customer_address || '---';
    document.getElementById('detailOrderNote').textContent = o.note || 'Không có ghi chú';

    // Thanh toán
    const method = o.payment_method || 'COD';
    const methodEl = document.getElementById('detailPaymentMethod');
    methodEl.textContent = method === 'QR' ? 'Chuyển khoản QR' : 'Tiền mặt (COD)';
    
    const transferBox = document.getElementById('transferInfoBox');
    if (method === 'QR' && o.transaction_code) {
        transferBox.style.display = 'block';
        document.getElementById('detailTransactionCode').textContent = o.transaction_code;
    } else {
        transferBox.style.display = 'none';
    }

    // Trạng thái & Tổng tiền
    const statusInfo = statusMap[o.status] || { vi: o.status, class: 'p-badge-info' };
    const statusEl = document.getElementById('detailOrderStatus');
    statusEl.textContent = statusInfo.vi;
    statusEl.className = 'p-badge ' + statusInfo.class;
    document.getElementById('detailOrderTotal').textContent = formatCurrency(o.total);

    // Danh sách sản phẩm
    const itemsEl = document.getElementById('detailOrderItems');
    if (o.items && o.items.length > 0) {
        itemsEl.innerHTML = o.items.map(item => `
            <tr>
                <td>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <img src="${getImagePath(item.product_image)}" width="40" height="40" style="object-fit:cover; border-radius:5px;">
                        <span style="font-weight:700;">${item.product_name}</span>
                    </div>
                </td>
                <td style="font-weight:700;">${item.size}</td>
                <td style="text-align:center;">${item.quantity}</td>
                <td style="text-align:right;">${formatCurrency(item.price)}</td>
                <td style="text-align:right; font-weight:800; color:#b00000;">${formatCurrency(parseFloat(sanitizeNumericString(item.price)) * item.quantity)}</td>
            </tr>
        `).join('');
    } else {
        itemsEl.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:20px; color:#888;">Không có dữ liệu sản phẩm</td></tr>';
    }

    const overlay = document.getElementById('orderDetailOverlay');
    overlay.style.display = 'flex';
    setTimeout(() => overlay.style.opacity = '1', 10);
}

// logic đóng xem chi tiết đơn hàng
function closeOrderDetail() {
    const overlay = document.getElementById('orderDetailOverlay');
    overlay.style.opacity = '0';
    setTimeout(() => overlay.style.display = 'none', 300);
}

// logic xóa 
async function deleteProduct(id) {
    showConfirm('Xác nhận xóa', 'Bạn có chắc chắn muốn xóa sản phẩm này khỏi hệ thống?', async () => {
        try { 
            await webFetch(`/admin/products/${id}`, { method: 'DELETE' }); 
            await loadProducts();
            showToast('Thành công', 'Đã xóa sản phẩm thành công.');
        } catch (e) { 
            showToast('Lỗi', 'Không thể xóa sản phẩm.', 'error');
        }
    });
}

async function deleteBrand(id) {
    showConfirm('Xác nhận xóa', 'Bạn có chắc chắn muốn xóa thương hiệu này? Toàn bộ sản phẩm liên quan sẽ bị ảnh hưởng.', async () => {
        try { 
            await webFetch(`/admin/brands/${id}`, { method: 'DELETE' }); 
            await loadBrands(); 
            showToast('Thành công', 'Đã xóa thương hiệu thành công.');
        } catch (e) { 
            showToast('Lỗi', 'Không thể xóa thương hiệu.', 'error');
        }
    });
}

async function deleteUser(id) {
    showConfirm('Xác nhận xóa', 'Bạn có chắc chắn muốn xóa tài khoản người dùng này?', async () => {
        try { 
            await webFetch(`/admin/users/${id}`, { method: 'DELETE' }); 
            await loadUsers(); 
            showToast('Thành công', 'Đã xóa người dùng thành công.');
        } catch (e) { 
            showToast('Lỗi', 'Không thể xóa người dùng.', 'error');
        }
    });
}

// logic lấy dữ liệu để cập nhật sản phẩm
async function loadProductForEdit(id) {
    const p = products.find(x => x.id == id);
    if (!p) return;
    document.getElementById('editProductId').value = p.id;
    document.getElementById('editProductName').value = p.name;
    document.getElementById('editProductPrice').value = p.price ? new Intl.NumberFormat('vi-VN').format(parseInt(p.price, 10)) : '';
    document.getElementById('editProductGender').value = p.gender || 'Unisex';
    document.getElementById('editProductDescription').value = p.description || '';
    document.getElementById('editProductBrand').value = p.brand_id;
    // Show current image preview
    const preview = document.getElementById('editProductPreview');
    if (preview) preview.innerHTML = p.image
        ? `<img src="${getImagePath(p.image)}" style="height:60px; object-fit:cover; border:1px solid #eee; margin-top:4px;">`
        : '';
    // Populate brand dropdown with current brands
    const brandSel = document.getElementById('editProductBrand');
    if (brandSel && brands.length > 0) {
        brandSel.innerHTML = brands.map(b =>
            `<option value="${b.id}" ${b.id == p.brand_id ? 'selected' : ''}>${b.name}</option>`
        ).join('');
    }
}

// logic xử lý cập nhật sản phẩm
async function handleProductEdit(e) {
    e.preventDefault();
    const id = document.getElementById('editProductId').value;
    const fd = new FormData();
    fd.append('name', document.getElementById('editProductName').value);
    fd.append('brand_id', document.getElementById('editProductBrand').value);
    fd.append('price', document.getElementById('editProductPrice').value.replace(/[^\d]/g, ''));
    fd.append('gender', document.getElementById('editProductGender').value);
    fd.append('description', document.getElementById('editProductDescription').value);
    const img = document.getElementById('editProductImage').files[0];
    if (img) fd.append('image', img);
    try { 
        await webFetch(`/admin/products/${id}`, { method: 'POST', body: fd }); 
        await loadProducts(); 
        closeEditForm(); 
        showToast('Thành công', 'Thông tin sản phẩm đã được cập nhật.');
    } catch (e) { 
        showToast('Lỗi', 'Cập nhật thất bại: ' + e.message, 'error');
    }
}

// logic lấy dữ liệu để cập nhật thương hiệu
async function loadBrandForEdit(id) {
    const b = brands.find(x => x.id == id);
    if (!b) return;
    document.getElementById('editBrandId').value = b.id;
    document.getElementById('editBrandName').value = b.name;
    const preview = document.getElementById('editBrandPreview');
    if (preview) preview.innerHTML = b.logo
        ? `<img src="${getImagePath(b.logo)}" style="height:50px; object-fit:contain; border:1px solid #eee; margin-top:4px;">`
        : '';
}

// logic xử lý cập nhật thương hiệu
async function handleBrandEdit(e) {
    e.preventDefault();
    const id = document.getElementById('editBrandId').value;
    const fd = new FormData();
    fd.append('name', document.getElementById('editBrandName').value);
    const logo = document.getElementById('editBrandLogo').files[0];
    if (logo) fd.append('logo', logo);
    try { 
        await webFetch(`/admin/brands/${id}`, { method: 'POST', body: fd });
        await loadBrands();
        closeEditForm();
        showToast('Thành công', 'Đã cập nhật thương hiệu.');
    } catch (e) { 
        showToast('Lỗi', 'Cập nhật thất bại: ' + e.message, 'error');
    }
}

// logic lấy dữ liệu để cập nhật người dùng
async function loadUserForEdit(id) {
    const u = users.find(x => x.id == id);
    if (!u) return;
    document.getElementById('editUserId').value = u.id;
    document.getElementById('editUserName').value = u.name || '';
    document.getElementById('editUserUsername').value = u.username || '';
    document.getElementById('editUserEmail').value = u.email;
    document.getElementById('editUserRole').value = u.role || 'user';
    document.getElementById('editUserAddress').value = u.address || '';
}

// logic xử lý cập nhật người dùng  
async function handleUserEdit(e) {
    e.preventDefault();
    const id = document.getElementById('editUserId').value;
    const fd = new FormData();
    fd.append('_method', 'PUT');
    fd.append('name', document.getElementById('editUserName').value);
    fd.append('username', document.getElementById('editUserUsername').value);
    fd.append('email', document.getElementById('editUserEmail').value);
    fd.append('role', document.getElementById('editUserRole').value);
    fd.append('address', document.getElementById('editUserAddress').value);
    const pass = document.getElementById('editUserPassword').value;
    if (pass) fd.append('password', pass);
    try { 
        await webFetch(`/admin/users/${id}`, { method: 'POST', body: fd }); 
        await loadUsers(); 
        closeEditForm(); 
        showToast('Thành công', 'Đã cập nhật thông tin người dùng.');
    } catch (e) { 
        showToast('Lỗi', 'Cập nhật thất bại: ' + e.message, 'error');
    }
}

// logic Dashboard
function updateDashboard() {
    const rev = orders.reduce((s, o) => s + (['confirmed', 'shipped', 'delivered'].includes(o.status) ? parseFloat(o.total) : 0), 0);
    document.getElementById('totalRevenue').textContent = formatCurrency(rev);
    document.getElementById('totalOrders').textContent = orders.length;
    document.getElementById('totalProducts').textContent = products.length;
    document.getElementById('totalUsersCount').textContent = users.length;
    
    renderBreakdowns(orders, products);
    renderUnifiedChart();
    renderRevenueSection();
}

function renderRevenueSection() {
    // 1. Tính toán doanh thu
    const realRev = orders.reduce((s, o) => s + (['delivered', 'completed'].includes(o.status) ? parseFloat(o.total) : 0), 0);
    const pendRev = orders.reduce((s, o) => s + (['pending', 'confirmed', 'shipped', 'processing'].includes(o.status) ? parseFloat(o.total) : 0), 0);
    
    if (document.getElementById('realRevenueDetail')) document.getElementById('realRevenueDetail').textContent = formatCurrency(realRev);
    if (document.getElementById('pendingRevenueDetail')) document.getElementById('pendingRevenueDetail').textContent = formatCurrency(pendRev);

    // 2. Biểu đồ Doanh thu (Cột dọc)
    const ctxBar = document.getElementById('revenueBarChart')?.getContext('2d');
    if (ctxBar) {
        if (revenueBarChart) revenueBarChart.destroy();
        const days = 14;
        const labels = [];
        const revenueData = [];
        for (let i = days - 1; i >= 0; i--) {
            const d = new Date(); d.setDate(d.getDate() - i);
            const s = d.toISOString().split('T')[0];
            labels.push(d.toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit' }));
            const dayRevenue = orders.filter(o => o.created_at.startsWith(s)).reduce((acc, o) => acc + parseFloat(o.total || 0), 0);
            revenueData.push(dayRevenue);
        }
        revenueBarChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels,
                datasets: [{ label: 'Doanh thu (VNĐ)', data: revenueData, backgroundColor: '#b00000', borderRadius: 4 }]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { callback: v => formatCurrency(v) } } } }
        });
    }

    // 3. Biểu đồ Tỷ lệ thanh toán (Tròn)
    const ctxPie = document.getElementById('paymentPieChart')?.getContext('2d');
    if (ctxPie) {
        if (paymentPieChart) paymentPieChart.destroy();
        const stats = { 'QR': 0, 'COD': 0 };
        orders.forEach(o => stats[o.payment_method === 'QR' ? 'QR' : 'COD']++);
        paymentPieChart = new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Chuyển khoản QR', 'Tiền mặt (COD)'],
                datasets: [{ data: [stats['QR'], stats['COD']], backgroundColor: ['#b00000', '#064e3b'], borderWidth: 0 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } }, cutout: '70%' }
        });
    }

    // 4. Biểu đồ Top mẫu giày (Cột ngang)
    const ctxShoes = document.getElementById('topShoesChart')?.getContext('2d');
    if (ctxShoes) {
        if (topShoesChart) topShoesChart.destroy();
        const shoeStats = {};
        orders.forEach(o => o.items?.forEach(i => { shoeStats[i.product_name] = (shoeStats[i.product_name] || 0) + i.quantity; }));
        const sorted = Object.entries(shoeStats).sort(([,a],[,b]) => b-a).slice(0, 5);
        topShoesChart = new Chart(ctxShoes, {
            type: 'bar',
            data: {
                labels: sorted.map(([n]) => n),
                datasets: [{ label: 'Số lượng bán', data: sorted.map(([,q]) => q), backgroundColor: '#b00000', borderRadius: 4 }]
            },
            options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });
    }

    // 5. Biểu đồ Top thương hiệu (Cột ngang)
    const ctxBrands = document.getElementById('topBrandsChart')?.getContext('2d');
    if (ctxBrands) {
        if (topBrandsChart) topBrandsChart.destroy();
        const brandStats = {};
        orders.forEach(o => o.items?.forEach(i => { 
            const p = products.find(prod => prod.name === i.product_name);
            const bName = p?.brand?.name || 'Khác';
            brandStats[bName] = (brandStats[bName] || 0) + i.quantity; 
        }));
        const sortedB = Object.entries(brandStats).sort(([,a],[,b]) => b-a).slice(0, 5);
        topBrandsChart = new Chart(ctxBrands, {
            type: 'bar',
            data: {
                labels: sortedB.map(([n]) => n),
                datasets: [{ label: 'Số lượng bán', data: sortedB.map(([,q]) => q), backgroundColor: '#064e3b', borderRadius: 4 }]
            },
            options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });
    }

    // 6. Bảng Top Khách hàng
    const customerTable = document.getElementById('topCustomersTable');
    if (customerTable) {
        const custStats = {};
        orders.forEach(o => {
            const uid = o.user_id || 'guest';
            const name = o.user ? (o.user.username || o.user.name) : (o.customer_name || 'Khách vãng lai');
            if (!custStats[uid]) custStats[uid] = { name, count: 0, spent: 0 };
            custStats[uid].count++;
            custStats[uid].spent += parseFloat(o.total);
        });
        const sortedC = Object.values(custStats).sort((a, b) => b.spent - a.spent).slice(0, 5);
        customerTable.innerHTML = sortedC.map(c => `
            <tr>
                <td style="font-weight:700;">${c.name}</td>
                <td style="text-align:center; font-weight:800;">${c.count} đơn</td>
                <td style="text-align:center; font-weight:800; color:#b00000;">${formatCurrency(c.spent)}</td>
                <td style="text-align:center;"><span class="p-badge ${c.spent > 10000000 ? 'p-badge-danger' : 'p-badge-info'}">${c.spent > 10000000 ? 'Kim cương' : 'Thành viên'}</span></td>
            </tr>
        `).join('');
    }
}

// logic biểu đồ
function renderUnifiedChart() {
    const ctx = document.getElementById('unifiedChart')?.getContext('2d');
    if (!ctx) return;
    if (unifiedChart) unifiedChart.destroy();
    
    const days = 14;
    const labels = [];
    const orderData = [];
    const revData = [];
    
    for (let i = days - 1; i >= 0; i--) {
        const d = new Date(); d.setDate(d.getDate() - i);
        const s = d.toISOString().split('T')[0];
        labels.push(d.toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit' }));
        const dayOrders = orders.filter(o => o.created_at.startsWith(s));
        orderData.push(dayOrders.length);
        revData.push(dayOrders.reduce((acc, o) => acc + parseFloat(o.total || 0), 0) / 1000000); // in millions
    }

    unifiedChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [
                { label: 'Đơn hàng', data: orderData, borderColor: '#000', backgroundColor: 'rgba(0,0,0,0.05)', fill: true, tension: 0.4 },
                { label: 'Doanh thu (Triệu)', data: revData, borderColor: '#10b981', tension: 0.4 }
            ]
        },
        options: { responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false } }
    });
}

// logic tải dữ liệu ban đầu 
document.addEventListener('DOMContentLoaded', async function() {
    csrfToken = getCsrfToken();
    injectEditModal();

    // Sidebar & Navigation
    const sidebarItems = document.querySelectorAll('.sidebar-menu li[data-section]');
    sidebarItems.forEach(item => {
        item.addEventListener('click', () => {
            sidebarItems.forEach(i => i.classList.remove('active'));
            item.classList.add('active');
            const sectionId = item.getAttribute('data-section');
            document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
            document.getElementById(sectionId)?.classList.add('active');
            
            const title = item.querySelector('span').textContent;
            document.getElementById('current-section-title').textContent = title;
        });
    });

    // Mobile Toggle
    const toggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    if (toggle && sidebar) {
        toggle.addEventListener('click', (e) => { e.stopPropagation(); sidebar.classList.toggle('active'); });
        document.addEventListener('click', (e) => { if (!sidebar.contains(e.target) && !toggle.contains(e.target)) sidebar.classList.remove('active'); });
    }

    // Load All Data
    await Promise.all([loadProducts(), loadBrands(), loadUsers(), loadOrders()]);
    updateDashboard();
    renderNotifications();

    // Toggle Add Forms (Unified Modal)
    function showAddForm(formId, title, iconClass) {
        document.getElementById('productEntryForm').style.display = 'none';
        document.getElementById('brandForm').style.display = 'none';
        document.getElementById('userForm').style.display = 'none';
        
        document.getElementById(formId).style.display = 'block';
        document.getElementById('addModalTitle').innerText = title;
        document.getElementById('addModalIcon').className = 'bx ' + iconClass;
        
        const overlay = document.getElementById('addFormOverlay');
        overlay.style.display = 'flex';
        
        // Cài đặt tắt modal khi click ra ngoài overlay
        overlay.onclick = function(e) {
            if (e.target === overlay) overlay.style.display = 'none';
        };
    }

    document.getElementById('toggleProductFormBtn')?.addEventListener('click', () => showAddForm('productEntryForm', 'Thêm sản phẩm mới', 'bx-plus'));
    document.getElementById('toggleBrandFormBtn')?.addEventListener('click', () => showAddForm('brandForm', 'Thêm hãng mới', 'bx-plus'));
    document.getElementById('toggleUserFormBtn')?.addEventListener('click', () => showAddForm('userForm', 'Tạo tài khoản', 'bx-user-plus'));

    // New Submissions
    document.getElementById('productEntryForm')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const fd = new FormData();
        fd.append('name', document.getElementById('productName').value);
        fd.append('price', document.getElementById('productPrice').value.replace(/[^\d]/g, ''));
        fd.append('brand_id', document.getElementById('productCategory').value);
        fd.append('gender', document.getElementById('productGender').value);
        fd.append('description', document.getElementById('productDescription').value);
        const img = document.getElementById('productImage').files[0];
        if (img) fd.append('image', img);
        try { 
            await webFetch('/admin/products', { method: 'POST', body: fd }); 
            await loadProducts(); 
            e.target.reset(); 
            document.getElementById('addFormOverlay').style.display = 'none'; 
            showToast('Thành công', 'Sản phẩm mới đã được thêm vào hệ thống.');
        } catch (err) { 
            showToast('Lỗi', 'Không thể thêm sản phẩm: ' + err.message, 'error');
        }
    });

    document.getElementById('brandForm')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const fd = new FormData();
        fd.append('name', document.getElementById('brandName').value);
        const logo = document.getElementById('brandLogo').files[0];
        if (logo) fd.append('logo', logo);
        try { 
            await webFetch('/admin/brands', { method: 'POST', body: fd }); 
            await loadBrands(); 
            e.target.reset(); 
            document.getElementById('addFormOverlay').style.display = 'none'; 
            showToast('Thành công', 'Thương hiệu đã được tạo.');
        } catch (err) { 
            showToast('Lỗi', 'Không thể tạo thương hiệu: ' + err.message, 'error');
        }
    });

    document.getElementById('userForm')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const fd = new FormData();
        fd.append('username', document.getElementById('userEmail').value.split('@')[0]); // Fallback username
        fd.append('email', document.getElementById('userEmail').value);
        fd.append('password', document.getElementById('userPassword').value);
        fd.append('password_confirmation', document.getElementById('userPassword').value);
        fd.append('role', document.getElementById('userRole').value);
        fd.append('address', document.getElementById('userAddress').value);
        try { 
            await webFetch('/admin/users', { method: 'POST', body: fd }); 
            await loadUsers(); 
            e.target.reset(); 
            document.getElementById('addFormOverlay').style.display = 'none'; 
            showToast('Thành công', 'Đã tạo tài khoản mới.');
        } catch (err) { 
            showToast('Lỗi', 'Không thể tạo tài khoản: ' + err.message, 'error');
        }
    });

    // Excel Export
    document.getElementById('exportExcelBtn')?.addEventListener('click', () => {
        if (orders.length === 0) return showToast('Thông báo', 'Không có dữ liệu để xuất.', 'info');
        const ws = XLSX.utils.json_to_sheet(orders.map(o => ({
            'ID': o.id, 'Khách hàng': o.user ? o.user.username : 'Khách', 'Tổng': formatCurrency(o.total), 'Trạng thái': getVietnameseStatus(o.status), 'Ngày': new Date(o.created_at).toLocaleString('vi-VN')
        })));
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Orders");
        XLSX.writeFile(wb, "LumaShoes_Orders.xlsx");
    });

    // Notifications
    setupNotifications();
    setupUserDropdown();
});

// down người dùng
function setupUserDropdown() {
    const icon = document.getElementById('user-icon');
    const dropdown = document.getElementById('userDropdown');
    if (!icon || !dropdown) return;
    
    icon.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });
    
    document.addEventListener('click', () => {
        if (dropdown) dropdown.style.display = 'none';
    });
}

// logic thông báo
let readNotifs = new Set();
function setupNotifications() {
    const bell = document.getElementById('bell-icon');
    const list = document.getElementById('notificationList');
    if (!bell || !list) return;

    // Gọi lần đầu để hiển thị badge ngay nếu đã có dữ liệu
    renderNotifications();

    bell.addEventListener('click', (e) => {
        e.stopPropagation();
        list.style.display = list.style.display === 'none' ? 'block' : 'none';
        renderNotifications();
    });
    document.addEventListener('click', () => list.style.display = 'none');
}

//logic render thông báo
function renderNotifications() {
    const list = document.getElementById('notificationList');
    const badge = document.getElementById('notificationBadge');
    
    const notifs = [];
    products.filter(p => (p.stock || 0) <= 5).forEach(p => notifs.push({ id: `stock-${p.id}`, msg: `Sản phẩm "${p.name}" sắp hết hàng (${p.stock})`, type: 'warn' }));
    orders.filter(o => o.status === 'pending').forEach(o => notifs.push({ id: `order-${o.id}`, msg: `Đơn hàng mới #${o.id} đang chờ xử lý`, type: 'info' }));
    
    const unread = notifs.filter(n => !readNotifs.has(n.id));
    badge.textContent = unread.length;
    badge.style.display = unread.length > 0 ? 'block' : 'none';

    list.innerHTML = notifs.length === 0 ? '<li style="padding:10px; color:#666;">Không có thông báo mới</li>' : notifs.map(n => `
        <li style="padding:10px; border-bottom:1px solid #eee; cursor:pointer; background:${readNotifs.has(n.id) ? '#fff' : '#f0f7ff'}" onclick="readNotifs.add('${n.id}'); renderNotifications();">
            <div style="font-size:0.8rem;">${n.msg}</div>
        </li>`).join('');
}

// hiển thị form thay mật khẩu
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('togglePasswordBtn');
    const passwordForm = document.getElementById('passwordForm');

    if (toggleBtn && passwordForm) {
        toggleBtn.addEventListener('click', function() {
            const isHidden = passwordForm.style.display === 'none';
            passwordForm.style.display = isHidden ? 'block' : 'none';
            // Đổi nhãn nút và icon cho sinh động
            this.innerHTML = isHidden ?
                '<i class="bx bx-x"></i> Hủy thay đổi' :
                '<i class="bx bx-lock-open-alt"></i> Thay mật khẩu mới';
            this.classList.toggle('p-btn-outline', isHidden);
            this.classList.toggle('p-btn-dark', !isHidden);
        });
    }
});


// logic quản lý size
let currentSizeProductId = null;

function openSizeModal(productId, productName) {
    currentSizeProductId = productId;
    document.getElementById('sizeModalTitle').textContent = 'Quản lý Size — ' + productName;
    document.getElementById('sizeModalProductName').textContent = 'ID sản phẩm: ' + productId;
    document.getElementById('newSizeValue').value = '';
    document.getElementById('newSizeStock').value = '';

    const overlay = document.getElementById('sizeModalOverlay');
    overlay.style.display = 'flex';
    setTimeout(() => overlay.style.opacity = '1', 10);

    loadSizeTable();

    // Đóng khi click ngoài
    overlay.onclick = (e) => { if (e.target === overlay) closeSizeModal(); };
}

function closeSizeModal() {
    const overlay = document.getElementById('sizeModalOverlay');
    overlay.style.display = 'none';
    currentSizeProductId = null;
}

// logic hiển thị bảng size
async function loadSizeTable() {
    const tbody = document.getElementById('sizeTableBody');
    tbody.innerHTML = '<tr><td colspan="3" style="text-align:center;padding:16px;color:#aaa;">Đang tải...</td></tr>';
    try {
        const res = await webFetch(`/admin/products/${currentSizeProductId}/sizes`);
        const data = await res.json();
        renderSizeTable(data.sizes || []);
        // Cập nhật lại sizes trong mảng products local để chip cập nhật ngay
        const p = products.find(x => x.id == currentSizeProductId);
        if (p) { p.sizes = data.sizes || []; renderProducts(); }
    } catch (e) {
        tbody.innerHTML = '<tr><td colspan="3" style="color:#ef4444;padding:10px;">Lỗi tải sizes.</td></tr>';
    }
}

// logic render bảng size
function renderSizeTable(sizes) {
    const tbody = document.getElementById('sizeTableBody');
    if (!sizes || sizes.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3" style="text-align:center;padding:16px;color:#aaa;">Chưa có size nào.</td></tr>';
        return;
    }
    // Sắp xếp theo size tăng dần (hỗ trợ số)
    sizes.sort((a, b) => parseFloat(a.size) - parseFloat(b.size) || a.size.localeCompare(b.size));
    tbody.innerHTML = sizes.map(z => {
        const stockColor = z.stock === 0 ? '#ef4444' : (z.stock <= 5 ? '#d97706' : '#16a34a');
        return `
        <tr id="sizeRow_${z.id}">
            <td id="sizeCell_${z.id}" style="font-weight:700;">${z.size}</td>
            <td>
                <span id="sizeStock_${z.id}" style="font-weight:800; color:${stockColor};">${z.stock}</span>
            </td>
            <td>
                <div style="display:flex;gap:5px;">
                    <button class="btn-icon btn-icon-warning" title="Chỉnh sửa" onclick="startEditSize(${z.id},'${z.size}',${z.stock})"><i class='bx bx-edit-alt'></i></button>
                    <button class="btn-icon btn-icon-danger" title="Xóa" onclick="deleteSize(${z.id})"><i class='bx bx-trash'></i></button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

// logic chỉnh sửa size
function startEditSize(sizeId, sizeVal, stockVal) {
    // Chuyển dòng thành inline-edit
    const sizeCell  = document.getElementById(`sizeCell_${sizeId}`);
    const stockCell = document.getElementById(`sizeStock_${sizeId}`);
    const row = document.getElementById(`sizeRow_${sizeId}`);

    if (!sizeCell || !stockCell) return;

    sizeCell.innerHTML  = `<input type="text" class="form-control" id="editSizeVal_${sizeId}" value="${sizeVal}" style="width:70px;padding:4px 8px;font-size:0.85rem;">`;
    stockCell.innerHTML = `<input type="number" class="form-control" id="editStockVal_${sizeId}" value="${stockVal}" min="0" style="width:80px;padding:4px 8px;font-size:0.85rem;">`;

    // Thay nút thao tác
    row.cells[2].innerHTML = `
        <div style="display:flex;gap:5px;">
            <button class="btn-icon" style="background:#10b981;color:#fff;" title="Lưu" onclick="saveEditSize(${sizeId})"><i class='bx bx-check'></i></button>
            <button class="btn-icon btn-icon-warning" title="Hủy" onclick="loadSizeTable()"><i class='bx bx-x'></i></button>
        </div>`;
}

// logic lưu size đã chỉnh sửa
async function saveEditSize(sizeId) {
    const sizeVal  = document.getElementById(`editSizeVal_${sizeId}`)?.value.trim();
    const stockVal = document.getElementById(`editStockVal_${sizeId}`)?.value;

    if (!sizeVal || stockVal === '') return showToast('Lỗi', 'Vui lòng nhập đầy đủ thông tin.', 'error');

    try {
        await webFetch(`/admin/products/${currentSizeProductId}/sizes/${sizeId}`, {
            method: 'POST',
            body: JSON.stringify({ size: sizeVal, stock: parseInt(stockVal) })
        });
        showToast('Thành công', 'Đã cập nhật size.');
        await loadSizeTable();
    } catch (e) {
        showToast('Lỗi', 'Cập nhật thất bại: ' + e.message, 'error');
    }
}

// logic thêm size mới
async function addSize() {
    const sizeVal  = document.getElementById('newSizeValue').value.trim();
    const stockVal = document.getElementById('newSizeStock').value;

    if (!sizeVal || stockVal === '') return showToast('Lỗi', 'Vui lòng nhập Size và Số lượng.', 'error');

    try {
        await webFetch(`/admin/products/${currentSizeProductId}/sizes`, {
            method: 'POST',
            body: JSON.stringify({ size: sizeVal, stock: parseInt(stockVal) })
        });
        document.getElementById('newSizeValue').value = '';
        document.getElementById('newSizeStock').value = '';
        showToast('Thành công', `Đã thêm size ${sizeVal}.`);
        await loadSizeTable();
    } catch (e) {
        showToast('Lỗi', 'Thêm thất bại: ' + e.message, 'error');
    }
}

// logic xóa size
async function deleteSize(sizeId) {
    showConfirm('Xác nhận xóa', 'Bạn có chắc chắn muốn xóa size này?', async () => {
        try {
            await webFetch(`/admin/products/${currentSizeProductId}/sizes/${sizeId}`, { method: 'DELETE' });
            showToast('Thành công', 'Đã xóa size.');
            await loadSizeTable();
        } catch (e) {
            showToast('Lỗi', 'Xóa thất bại: ' + e.message, 'error');
        }
    });
}
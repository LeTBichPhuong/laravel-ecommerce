// ══ Global UI Utilities (Toasts & Modals) ══

function showToast(message, type = 'success') {
    let container = document.querySelector('.studio-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'studio-toast-container';
        document.body.appendChild(container);
    }
    const toast = document.createElement('div');
    toast.className = `studio-toast ${type}`;
    const icon = type === 'success' ? 'bx-check-circle' : 'bx-error-circle';
    toast.innerHTML = `
        <i class="bx ${icon} studio-toast-icon"></i>
        <span class="studio-toast-msg">${message}</span>
    `;
    container.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 10);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 500);
    }, 4000);
}

function showConfirm(options, onApprove) {
    const { title, text, icon } = options;
    const overlay = document.createElement('div');
    overlay.className = 'studio-modal-overlay';
    overlay.innerHTML = `
        <div class="studio-modal">
            <i class="bx ${icon || 'bx-help-circle'} studio-modal-icon"></i>
            <div class="studio-modal-title">${title || 'XÁC NHẬN'}</div>
            <p class="studio-modal-text">${text || 'Bạn có chắc chắn muốn thực hiện hành động này?'}</p>
            <div class="studio-modal-actions">
                <button class="studio-modal-btn studio-modal-btn-cancel">Đóng</button>
                <button class="studio-modal-btn studio-modal-btn-approve">Đồng ý</button>
            </div>
        </div>
    `;
    document.body.appendChild(overlay);
    setTimeout(() => overlay.classList.add('show'), 10);

    const close = () => {
        overlay.classList.remove('show');
        setTimeout(() => overlay.remove(), 400);
    };

    overlay.querySelector('.studio-modal-btn-cancel').onclick = close;
    overlay.querySelector('.studio-modal-btn-approve').onclick = () => {
        close();
        if (onApprove) onApprove();
    };
    overlay.onclick = (e) => { if (e.target === overlay) close(); };
}

// Chức năng tìm kiếm sản phẩm và thương hiệu theo thời gian thực
document.addEventListener('DOMContentLoaded', function () {
    const searchIcon = document.getElementById('searchIcon');
    const searchForm = document.getElementById('searchForm');
    const input = searchForm.querySelector('input[name="keyword"]');
    const resultsBox = document.getElementById('searchResults');

    // Hàm chống spam
    function debounce(fn, wait = 220) {
        let t;
        return (...args) => {
            clearTimeout(t);
            t = setTimeout(() => fn.apply(this, args), wait);
        };
    }

    // Ẩn/hiện ô tìm kiếm khi nhấn vào icon kính lúp
    searchIcon.addEventListener('click', function (e) {
        e.preventDefault();
        if (searchForm.style.display === 'block') {
            searchForm.style.display = 'none';
            resultsBox.style.display = 'none';
        } else {
            searchForm.style.display = 'block';
            input.focus();
        }
    });

    // Ngăn form tự submit khi nhấn Enter
    searchForm.addEventListener('submit', function (e) {
        e.preventDefault();
    });

    // Ẩn khung kết quả khi click ra ngoài khu vực tìm kiếm
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.search-container')) {
            searchForm.style.display = 'none';
            resultsBox.style.display = 'none';
        }
    });

    // Gửi yêu cầu đến route để lấy dữ liệu JSON
    async function fetchSuggestions(q) {
        try {
            const res = await fetch(`/ajax/tim-kiem?keyword=${encodeURIComponent(q)}`, {
                headers: { 'Accept': 'application/json' }
            });
            if (!res.ok) {
                console.error('Kết quả trả về không hợp lệ', res.status);
                return null;
            }
            const json = await res.json();
            return json;
        } catch (err) {
            console.error('Lỗi khi gọi API tìm kiếm', err);
            return null;
        }
    }

    // Hiển thị khi không có kết quả
    function renderEmpty() {
        resultsBox.innerHTML = '<p style="padding:12px;color:#666;">Không tìm thấy kết quả</p>';
        resultsBox.style.display = 'block';
    }

    // Hiển thị danh sách kết quả (sản phẩm + thương hiệu)
    function renderResults(data) {
        if (!data) {
            renderEmpty();
            return;
        }
        const products = data.products || [];
        const brands = data.brands || [];

        if (products.length === 0 && brands.length === 0) {
            renderEmpty();
            return;
        }

        let html = '';

        // Hiển thị danh sách sản phẩm
        if (products.length > 0) {
            html += '<h4>Sản phẩm</h4>'; 
            products.forEach(p => {
                const img = p.image ? (p.image.startsWith('http') ? p.image : '/storage/' + p.image) : '/img/placeholder-product.png';
                let price = '';
                if (p.price) {
                    const val = String(p.price).trim();
                    if (val.includes('₫') || val.includes('đ')) {
                        price = val;
                    } else if (!isNaN(val)) {
                        price = Number(val).toLocaleString('vi-VN') + ' ₫';
                    } else {
                        price = val;
                    }
                }
                
                // Dùng slug hoặc ID cho URL an toàn hơn (thay vì encode name)
                const productUrl = `/san-pham/${p.id}-${encodeURIComponent(p.name.replace(/[^a-zA-Z0-9\s]/g, ''))}`;  // Ví dụ slug đơn giản
                
                html += `
                    <a class="search-item" href="${productUrl}">
                        <img src="${img}" alt="${escapeHtml(p.name)}" onerror="this.src='/img/placeholder-product.png'">
                        <div>
                            <div style="font-weight:500;">${escapeHtml(p.name)}</div>
                            <div class="price">${price || 'Liên hệ'}</div>
                        </div>
                    </a>
                `;
            });
        }

        // Hiển thị danh sách thương hiệu
        if (brands.length > 0) {
            html += '<h4>Thương hiệu</h4>';
            brands.forEach(b => {
                let logo = '/img/no-logo.png';
                if (b.logo) {
                    if (b.logo.startsWith('http')) logo = b.logo;
                    else if (b.logo.startsWith('/storage/')) logo = b.logo;
                    else if (b.logo.startsWith('storage/')) logo = '/' + b.logo;
                    else logo = '/storage/' + b.logo;
                }
                html += `
                    <a class="search-item" href="/thuong-hieu/${encodeURIComponent(b.name)}">
                        <img src="${logo}" alt="${escapeHtml(b.name)}" onerror="this.src='/img/no-logo.png'">
                        <div style="font-weight:500;">${escapeHtml(b.name)}</div>
                    </a>
                `;
            });
        }

        resultsBox.innerHTML = html;
        resultsBox.style.display = 'block';
    }

    // Hàm chống lỗi 
    function escapeHtml(s) {
        return String(s || '').replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    // Bắt sự kiện người dùng gõ vào ô tìm kiếm
    const handleInput = debounce(async function () {
        const q = input.value.trim();
        if (q.length < 2) {
            resultsBox.style.display = 'none';
            return;
        }

        // Hiển thị trạng thái đang tải
        resultsBox.innerHTML = '<p style="padding:12px;color:#666;">Đang tìm...</p>';
        resultsBox.style.display = 'block';

        // Gửi yêu cầu tìm kiếm và hiển thị kết quả
        const json = await fetchSuggestions(q);
        renderResults(json);
    }, 250);

    // Gắn sự kiện input vào ô nhập
    input.addEventListener('input', handleInput);
});

// Sản phẩm
document.addEventListener("DOMContentLoaded", function () {
    const navItems = document.querySelectorAll(".nav-item");

    navItems.forEach(navItem => {
        const link = navItem.querySelector("a");
        const submenu = navItem.querySelector(".submenu");

        link.addEventListener("click", function (e) {
            if (submenu && e.target.closest(".submenu-toggle")) {
                e.preventDefault();
                e.stopPropagation();
                navItem.classList.toggle("active");
            } 
        });

        // Nếu muốn submenu mở khi hover
        navItem.addEventListener("mouseenter", () => navItem.classList.add("active"));
        navItem.addEventListener("mouseleave", () => navItem.classList.remove("active"));
    });

    // Ẩn menu khi click ra ngoài
    document.addEventListener("click", function (e) {
        navItems.forEach(navItem => {
            if (!navItem.contains(e.target)) {
                navItem.classList.remove("active");
            }
        });
    });
});


// Tài khoản
document.addEventListener("DOMContentLoaded", () => {
  const links = document.querySelectorAll(".account-sidebar a");
  const sections = document.querySelectorAll(".content-section, .account-section");

  // Ẩn tất cả section
  sections.forEach(s => (s.style.display = "none"));

  // Tìm link có active và mở đúng section
  const activeLink = document.querySelector(".account-sidebar a.active");
  if (activeLink) {
    const section = document.getElementById(activeLink.dataset.section);
    if (section) section.style.display = "block";
  }

  // Khi click vào menu bên trái
  links.forEach(link => {
    link.addEventListener("click", e => {
      e.preventDefault();

      // Xóa trạng thái active cũ
      links.forEach(l => l.classList.remove("active"));
      sections.forEach(s => (s.style.display = "none"));

      // Thêm active cho link mới
      link.classList.add("active");
      const section = document.getElementById(link.dataset.section);
      if (section) section.style.display = "block";
    });
  });
});

function toggleForm(id) {
  const form = document.getElementById(id);
  form.classList.toggle("form-visible");
}

// Thương hiệu nội bật
// Fetch dữ liệu brand từ API 
async function loadBrands() {
  let brands = [];
  try {
    const res = await fetch("/thuong-hieu-json");
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    brands = await res.json();
  } catch (e) {
    console.error("Không thể tải dữ liệu thương hiệu:", e);
    return;
  }

  // Lọc chỉ thương hiệu có logo 
  const brandsWithLogo = brands.filter(brand => brand.logo && brand.logo.trim() !== '');

  // Hàm shuffle mảng để lấy ngẫu nhiên
  function shuffle(array) {
    return array.slice().sort(() => Math.random() - 0.5);
  }

  // Lấy 12 thương hiệu ngẫu nhiên từ những brand có logo 
  const randomBrands = shuffle(brandsWithLogo).slice(0, 12);

  // Tạo HTML động cho brand-grid với link clickable
  let html = '';
  randomBrands.forEach(brand => {
    const logoUrl = brand.logo.startsWith('http') ? brand.logo : `/${brand.logo}`;
    html += `
      <a href="/thuong-hieu/${encodeURIComponent(brand.name)}" class="brand-link">
        <img src="${logoUrl}" alt="${brand.name}" loading="lazy">
      </a>
    `;
  });

  // Chèn HTML vào DOM
  const brandGrid = document.querySelector('.brand-grid');
  if (brandGrid) {
    brandGrid.innerHTML = html;
  }
}

// Chạy khi DOM load xong
document.addEventListener('DOMContentLoaded', loadBrands);

// Quản lý đơn hàng
document.addEventListener('DOMContentLoaded', function() {
    
    // Lấy CSRF token
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        if (meta) return meta.content;
        
        const input = document.querySelector('input[name="_token"]');
        if (input) return input.value;
        
        console.error('CSRF token not found!');
        return '';
    }

    // Hủy đơn hàng
    const cancelButtons = document.querySelectorAll('.cancel-order');
    
    cancelButtons.forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            
            const orderId = this.getAttribute('data-order-id');
            
            if (!orderId) {
                showToast('Không tìm thấy ID đơn hàng!', 'error');
                return;
            }

            showConfirm({
                title: 'HỦY ĐƠN HÀNG',
                text: 'Bạn có chắc chắn muốn hủy đơn hàng này không? Hành động này không thể hoàn tác.',
                icon: 'bx-error-alt'
            }, async () => {
                // Disable button và show loading
                const originalHTML = this.innerHTML;
                this.disabled = true;
                this.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang xử lý...';

                try {
                    const response = await fetch(`/don-hang/cancel/${orderId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();
                    if (data.success) {
                        showToast(data.message || 'Đã hủy đơn hàng thành công!');
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        showToast(data.message || 'Không thể hủy đơn hàng!', 'error');
                        this.disabled = false;
                        this.innerHTML = originalHTML;
                    }
                } catch (error) {
                    console.error('Cancel order error:', error);
                    showToast('Có lỗi xảy ra: ' + error.message, 'error');
                    this.disabled = false;
                    this.innerHTML = originalHTML;
                }
            });
        });
    });

    // Xem chi tiết đơn hàng 
    const viewDetailButtons = document.querySelectorAll('.view-order-detail');
    
    viewDetailButtons.forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            window.location.href = `/don-hang/${orderId}`;
        });
    });

    // Filter đơn hàng theo trạng thái
    const statusFilter = document.getElementById('order-status-filter');
    
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            const status = this.value;
            const orderCards = document.querySelectorAll('.order-card');
            
            orderCards.forEach(card => {
                if (status === 'all' || card.getAttribute('data-status') === status) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
});

// chatbot
function toggleChatbot() {
  const box = document.getElementById("chatbot-box");
  if (!box) {
    console.error("Element 'chatbot-box' not found");
    return;
  }
  box.style.display = box.style.display === "block" ? "none" : "block";
}

function appendMessage(message, sender = "bot") {
  const messages = document.getElementById("chatbot-messages");
  if (!messages) return;
  
  const msgDiv = document.createElement("div");
  msgDiv.classList.add("message", sender === "user" ? "user-message" : "bot-message");
  
  const p = document.createElement("p");
  
  // Xử lý định dạng Markdown đơn giản (chỉ dành cho Bot)
  if (sender === "bot") {
    let formattedMsg = String(message)
      .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>') // In đậm
      .replace(/\n/g, '<br>'); // Xuống dòng
    p.innerHTML = formattedMsg;
  } else {
    p.textContent = String(message);
  }
  
  msgDiv.appendChild(p);
  messages.appendChild(msgDiv);
  messages.scrollTop = messages.scrollHeight;
}

async function sendMessage() {
  const input = document.getElementById("chatbot-input");
  if (!input) {
    console.error("Element 'chatbot-input' not found");
    return;
  }
  
  const text = input.value.trim();
  if (!text) return;
  
  // Hiển thị tin nhắn người dùng
  appendMessage(text, "user");
  input.value = "";
  
  // Hiển thị trạng thái đang nhập
  const typingId = `typing-${Date.now()}`;
  const typingDiv = document.createElement("div");
  typingDiv.classList.add("message", "bot-message");
  typingDiv.dataset.typingId = typingId;
  
  const tp = document.createElement("p");
  tp.textContent = "Đang nhập...";
  typingDiv.appendChild(tp);
  
  const messages = document.getElementById("chatbot-messages");
  if (messages) {
    messages.appendChild(typingDiv);
    messages.scrollTop = messages.scrollHeight;
  }
  
  try {
    // Lấy CSRF token
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (!csrfMeta) {
      throw new Error("CSRF token not found");
    }
    
    const response = await fetch("/chatbot", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": csrfMeta.content,
        "Accept": "application/json"
      },
      body: JSON.stringify({ message: text })
    });
    
    let reply = "Xin lỗi, hệ thống bận.";
    
    if (response.ok) {
      try {
        const data = await response.json();
        reply = data.reply || data.message || reply;
      } catch (parseError) {
        console.error("Error parsing response:", parseError);
      }
    } else if (response.status === 419) {
      reply = "Phiên làm việc hết hạn. Vui lòng tải lại trang.";
    } else if (response.status === 429) {
      reply = "Bạn đang gửi tin nhắn quá nhanh. Vui lòng đợi 1 phút.";
    } else if (response.status >= 500) {
      reply = "Máy chủ đang gặp sự cố. Thử lại sau.";
    } else {
      try {
        const errorData = await response.json();
        reply = errorData.reply || errorData.message || reply;
      } catch (_) {
        console.error("Error reading error response");
      }
    }
    
    // Xóa trạng thái đang nhập
    const typingEl = document.querySelector(`.bot-message[data-typing-id="${typingId}"]`);
    if (typingEl) typingEl.remove();
    
    // Hiển thị phản hồi
    appendMessage(reply, "bot");
    
  } catch (error) {
    console.error("Chatbot error:", error);
    
    // Xóa trạng thái đang nhập
    const typingEl = document.querySelector(`.bot-message[data-typing-id="${typingId}"]`);
    if (typingEl) typingEl.remove();
    
    appendMessage("Xin lỗi, có lỗi xảy ra khi kết nối!", "bot");
  }
}

function handleKey(event) {
  if (event.key === "Enter") {
    event.preventDefault();
    sendMessage();
  }
}

// Hiển thị tin nhắn chào mừng khi load trang
document.addEventListener('DOMContentLoaded', function() {
  setTimeout(() => {
    appendMessage("Dạ, Luma Shoes xin chào! Tôi là trợ lý ảo Luma Shoes Bot. Tôi có thể giúp gì cho bạn trong việc chọn giày hôm nay ạ?", "bot");
  }, 500);
});
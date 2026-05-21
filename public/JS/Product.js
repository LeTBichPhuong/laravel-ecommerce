// SẢN PHẨM BÁN CHẠY
function showTab(category, event) {
    document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
    if (event && event.target) event.target.classList.add('active');

    document.querySelectorAll('.slider').forEach(slider => slider.classList.remove('active'));
    const activeSlider = document.querySelector(`.slider[data-tab="${category}"]`);
    if (activeSlider) activeSlider.classList.add('active');
}

function getProductsByGender(data) {
    const categories = { nam: [], nu: [], unisex: [] };

    data.forEach(brand => {
        brand.products.forEach(product => {
            let gender = product.gender.toLowerCase();

            if (gender === 'men') categories.nam.push(product);
            else if (gender === 'unisex') categories.unisex.push(product);
            else if (gender === 'women') categories.nu.push(product);
            // Fallback nếu có giá trị khác
        });
    });

    return categories;
}

// Định dạng hiển thị giá
function formatPrice(price) {
    if (!price && price !== 0) return "";

    // Nếu là string, thử tìm regex (cho dữ liệu cũ)
    if (typeof price === 'string') {
        const matches = price.match(/[\d\.]+ ₫/g);
        if (matches && matches.length === 2) {
            return `
                <div class="price-box">
                    <span class="original-price">${matches[0]}</span>
                    <span class="discounted-price">${matches[1]}</span>
                </div>
            `;
        }
        // Nếu đã có ký hiệu ₫ thì trả về luôn
        if (price.includes('₫')) return `<div class="price-box"><span class="discounted-price">${price}</span></div>`;
    }

    // Nếu là số hoặc string số, định dạng sang VND
    const num = parseFloat(price);
    if (!isNaN(num)) {
        const formatted = new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(num);

        return `
            <div class="price-box">
                <span class="discounted-price">${formatted}</span>
            </div>
        `;
    }

    return `<div class="price-box"><span class="discounted-price">${price}</span></div>`;
}

// Tạo slider riêng cho từng giới tính
function generateSliderHtml(products, tabId) {
    let randomProducts = products;
    if (products.length > 10) {
        randomProducts = [...products].sort(() => Math.random() - 0.5).slice(0, 10);
    }
    
    let html = `<div class="slider ${tabId === 'Men' ? 'active' : ''}" data-tab="${tabId}">`;
    if (randomProducts.length === 0) {
        html += '<div class="product" style="width:100%;"><p>Không có sản phẩm</p></div>';
    } else {
        randomProducts.forEach(product => {
            let outOfStockHtml = product.status === 'out_of_stock' ? '<span class="badge-out-stock">Hết hàng</span>' : '';
            let badgeHtml = Math.random() > 0.5 ? '<span class="badge-new">NEW</span>' : ''; // Fake NEW badge for demo
            
            html += `
                <div class="product">
                    <div class="product-img-wrapper" style="cursor:pointer;" onclick="if(typeof openQuickView === 'function') openQuickView(${product.id})">
                        ${badgeHtml}
                        <img src="${product.image}" alt="${product.name}"
                            onerror="this.src='https://placehold.co/150?text=No+Image';">
                        <span class="btn-quick-action"><i class="bx bx-shopping-bag"></i></span>
                    </div>
                    <a href="/san-pham/${product.id}" class="product-link" style="color:black; text-decoration:none;">
                        <p>${product.name}</p>
                    </a>
                    <div class="price-and-badge">
                        ${formatPrice(product.price)}
                        ${outOfStockHtml}
                    </div>
                </div>
            `;
        });
    }
    html += '</div>';
    return html;
}

// Render 3 slider riêng biệt
function renderSliders(data) {
    const categories = getProductsByGender(data);
    const namHtml = generateSliderHtml(categories.nam, 'Men');
    const nuHtml = generateSliderHtml(categories.nu, 'Women');
    const unisexHtml = generateSliderHtml(categories.unisex, 'Unisex');

    const container = document.querySelector('.slider-container');
    if (container) container.innerHTML = namHtml + nuHtml + unisexHtml;

    addDragScrollToSlider();
}

// Cho phép kéo trượt slider
function addDragScrollToSlider() {
    let currentSlider = null;
    let isDragging = false;
    let startX, scrollLeft;

    function initDrag(element) {
        element.addEventListener('mousedown', (e) => {
            isDragging = true;
            currentSlider = element;
            startX = e.pageX - element.offsetLeft;
            scrollLeft = element.scrollLeft;
            element.style.cursor = 'grabbing';
        });

        element.addEventListener('mouseleave', () => isDragging = false);
        element.addEventListener('mouseup', () => {
            isDragging = false;
            if (currentSlider) currentSlider.style.cursor = 'grab';
        });

        element.addEventListener('mousemove', (e) => {
            if (!isDragging || currentSlider !== element) return;
            e.preventDefault();
            const x = e.pageX - element.offsetLeft;
            const walk = (x - startX) * 2;
            element.scrollLeft = scrollLeft - walk;
        });
    }

    document.querySelectorAll('.slider').forEach(slider => {
        initDrag(slider);
        slider.style.cursor = 'grab';
    });
}

// Gọi API sản phẩm
document.addEventListener('DOMContentLoaded', function() {
    fetch('/san-pham-json')
        .then(response => {
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            return response.json();
        })
        .then(data => {
            if (data.error) console.error('Lỗi data:', data.error);
            else renderSliders(data);
        })
        .catch(error => console.error('Fetch error:', error));
});


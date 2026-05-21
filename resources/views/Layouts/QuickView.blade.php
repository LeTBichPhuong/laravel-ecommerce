<style>
    /* Quick View Modal Styles */
    .qv-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(2px);
    }

    .qv-modal {
        background: #fff;
        width: 700px;
        max-width: 95%;
        display: flex;
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        animation: qvFadeIn 0.3s ease;
    }

    @keyframes qvFadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .qv-close {
        position: absolute;
        top: 10px;
        right: 15px;
        color: #999;
        background: transparent;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        font-size: 24px;
        transition: color 0.3s;
    }

    .qv-close:hover {
        color: #000;
    }

    .qv-left {
        width: 50%;
        background: #f8f8f8;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        position: relative;
    }

    .qv-left img {
        width: 100%;
        max-height: 280px;
        object-fit: contain;
    }

    .qv-discount-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: #ff0000;
        color: #fff;
        padding: 4px 8px;
        font-size: 0.8rem;
        font-weight: bold;
        border-radius: 4px;
    }

    .qv-right {
        width: 50%;
        padding: 30px;
        display: flex;
        flex-direction: column;
    }

    .qv-title {
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 8px;
        line-height: 1.4;
        padding-right: 20px;
    }

    .qv-meta {
        font-size: 0.8rem;
        color: #666;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .qv-status {
        background: #4ade80;
        color: #fff;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: bold;
    }

    .qv-price-box {
        margin-bottom: 20px;
        display: flex;
        align-items: center;
    }

    .qv-price {
        font-size: 1.25rem;
        font-weight: bold;
        color: #ef4444;
    }

    .qv-price-old {
        text-decoration: line-through;
        color: #999;
        margin-left: 10px;
        font-size: 0.95rem;
    }

    .qv-sizes {
        margin-bottom: 25px;
    }

    .qv-sizes label {
        font-size: 0.8rem;
        margin-bottom: 10px;
        display: block;
        color: #333;
    }

    .qv-size-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .qv-size-btn {
        border: 1px solid #e5e7eb;
        background: #fff;
        padding: 6px 12px;
        cursor: pointer;
        border-radius: 4px;
        transition: all 0.2s;
        font-size: 0.8rem;
        color: #374151;
    }

    .qv-size-btn:hover {
        border-color: #9ca3af;
    }

    .qv-size-btn.active {
        border-color: #ef4444;
        color: #ef4444;
        position: relative;
    }

    .qv-size-btn.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        right: -1px;
        width: 0;
        height: 0;
        border-bottom: 10px solid #ef4444;
        border-left: 10px solid transparent;
    }

    .qv-action-row {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
    }

    .qv-qty {
        display: flex;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
        width: 90px;
    }

    .qv-qty button {
        background: #fff;
        border: none;
        width: 28px;
        cursor: pointer;
        font-size: 1.1rem;
        color: #374151;
    }

    .qv-qty button:hover {
        background: #f3f4f6;
    }

    .qv-qty input {
        width: 34px;
        border: none;
        text-align: center;
        font-weight: 600;
        border-left: 1px solid #e5e7eb;
        border-right: 1px solid #e5e7eb;
    }

    .qv-add-btn {
        flex: 1;
        padding: 8px;
        background: #333;
        color: #fff;
        border: none;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: background 0.3s;
    }

    .qv-add-btn:hover {
        background: #0f0f0f;
    }

    .qv-detail-link {
        font-size: 0.85rem;
        color: #374151;
        text-decoration: underline;
        align-self: flex-start;
    }

    .qv-detail-link:hover {
        color: #000;
    }

    @media (max-width: 768px) {
        .qv-modal {
            flex-direction: column;
            max-height: 90vh;
            overflow-y: auto;
        }

        .qv-left,
        .qv-right {
            width: 100%;
        }

        .qv-close {
            top: 10px;
            right: 10px;
        }
    }
</style>

<!-- Quick View Modal -->
<div class="qv-overlay" id="qv-modal">
    <div class="qv-modal">
        <button class="qv-close" onclick="closeQuickView()">✕</button>
        <div class="qv-left">
            <img src="" id="qv-image" alt="Product Image">
        </div>
        <div class="qv-right">
            <h2 class="qv-title" id="qv-name">Đang tải...</h2>
            <div class="qv-meta">
                <span id="qv-sku">SKU: N/A</span>
                <span class="qv-status">Còn hàng</span>
            </div>
            <div class="qv-price-box">
                <span class="qv-price" id="qv-price">0đ</span>
            </div>

            <form id="qv-form" action="" method="POST">
                @csrf
                <div class="qv-sizes">
                    <label>Kích thước: <span id="qv-selected-size-label"></span></label>
                    <div class="qv-size-grid" id="qv-size-grid">
                        <!-- Sizes rendered via JS -->
                    </div>
                    <input type="hidden" name="size" id="qv-size-input" value="Mặc định">
                </div>

                <div class="qv-action-row">
                    <div class="qv-qty">
                        <button type="button"
                            onclick="const q=document.getElementById('qv-qty-input'); if(q.value>1) q.value--;">-</button>
                        <input type="number" id="qv-qty-input" name="quantity" value="1" min="1">
                        <button type="button"
                            onclick="const q=document.getElementById('qv-qty-input'); q.value++;">+</button>
                    </div>
                    <button type="submit" class="qv-add-btn">Thêm vào giỏ</button>
                </div>
            </form>

            <a href="#" id="qv-detail-link" class="qv-detail-link">Xem chi tiết »</a>
        </div>
    </div>
</div>

<script>
    function formatPriceVND(price) {
        if (!price && price !== 0) return '';
        const num = parseFloat(price);
        if (isNaN(num)) return price;

        // Xử lý giá cũ (chỉ để demo vì csdl ko có old price)
        return new Intl.NumberFormat('vi-VN').format(num) + '₫';
    }

    function openQuickView(id) {
        const overlay = document.getElementById('qv-modal');
        overlay.style.display = 'flex';
        document.getElementById('qv-name').textContent = 'Đang tải...';
        document.getElementById('qv-size-grid').innerHTML = '';
        document.getElementById('qv-image').src = '/img/placeholder-product.png';

        fetch(`/san-pham/quick-view/${id}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const p = data.product;
                    document.getElementById('qv-name').textContent = p.name;
                    document.getElementById('qv-image').src = p.image && p.image.startsWith('http') ? p.image : (p
                        .image ? '/storage/' + p.image : '/img/placeholder-product.png');
                    document.getElementById('qv-sku').textContent = 'SKU: ' + (p.sku || p.id + '-LUMA');

                    let priceNum = parseFloat(p.price);
                    document.getElementById('qv-price').textContent = formatPriceVND(p.price);

                    document.getElementById('qv-form').action = `/gio-hang/add/${p.id}`;
                    document.getElementById('qv-detail-link').href = `/san-pham/${p.id}`;
                    document.getElementById('qv-qty-input').value = 1;

                    const sizeGrid = document.getElementById('qv-size-grid');
                    const sizeInput = document.getElementById('qv-size-input');
                    const sizeLabel = document.getElementById('qv-selected-size-label');
                    sizeGrid.innerHTML = '';

                    if (p.sizes && p.sizes.length > 0) {
                        p.sizes.forEach((s, idx) => {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'qv-size-btn';
                            if (idx === 0) {
                                btn.classList.add('active');
                                sizeInput.value = s.size;
                                sizeLabel.textContent = s.size;
                            }
                            btn.textContent = s.size;
                            btn.onclick = function() {
                                document.querySelectorAll('.qv-size-btn').forEach(b => b.classList
                                    .remove('active'));
                                this.classList.add('active');
                                sizeInput.value = s.size;
                                sizeLabel.textContent = s.size;
                            };
                            sizeGrid.appendChild(btn);
                        });
                    } else {
                        sizeGrid.innerHTML = '<span>Mặc định</span>';
                        sizeInput.value = 'Mặc định';
                        sizeLabel.textContent = 'Mặc định';
                    }
                } else {
                    alert('Không tìm thấy sản phẩm!');
                    closeQuickView();
                }
            })
            .catch(err => {
                console.error(err);
                alert('Lỗi kết nối!');
                closeQuickView();
            });
    }

    function closeQuickView() {
        document.getElementById('qv-modal').style.display = 'none';
    }

    document.getElementById('qv-modal').addEventListener('click', function(e) {
        if (e.target === this) closeQuickView();
    });
</script>

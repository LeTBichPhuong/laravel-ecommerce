document.addEventListener('DOMContentLoaded', () => {
    // Lấy CSRF token từ meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    
    // Xử lý chọn phương thức thanh toán
    document.querySelectorAll('input[name="payment-method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Cập nhật giá trị vào select ẩn
            document.getElementById('payment-method').value = this.value;
            
            // Ẩn/hiện QR code
            const qrSection = document.getElementById('qr-section');
            if (this.value === 'QR') {
                if (!qrSection) {
                    // Tạo section QR code nếu chưa có
                    createQRSection();
                } else {
                    qrSection.style.display = 'block';
                }
            } else {
                if (qrSection) {
                    qrSection.style.display = 'none';
                }
            }
        });
    });
    
    // Tạo section hiển thị QR code
    function createQRSection() {
        const qrHTML = `
            <div id="qr-section" class="card mt-3">
                <div class="card-body text-center">
                    <h5 class="mb-3">Quét mã QR để thanh toán</h5>
                    <img src="/img/qr-payment.jpg" alt="QR Code" class="img-fluid" style="max-width: 300px;">
                    <p class="mt-3 text-muted">Quét mã QR bằng ứng dụng ngân hàng của bạn</p>
                    <div class="alert alert-info mt-2" style="padding: 10px 0;">
                        <strong>Lưu ý:</strong> Sau khi chuyển khoản, vui lòng nhấn "Thanh toán ngay"
                    </div>
                </div>
            </div>
        `;
        document.querySelector('.card-payment').insertAdjacentHTML('afterend', qrHTML);
    }
    
    // Cập nhật số lượng
    document.querySelectorAll('.quantity').forEach(input => {
        const wrap = input.closest('.quantity-wrapper');
        if (wrap) {
            const minusBtn = wrap.querySelector('.minus');
            const plusBtn = wrap.querySelector('.plus');
            
            if (minusBtn) {
                minusBtn.addEventListener('click', function() {
                    let currentVal = parseInt(input.value) || 1;
                    if (currentVal > 1) {
                        input.value = currentVal - 1;
                        input.dispatchEvent(new Event('change'));
                    }
                });
            }
            
            if (plusBtn) {
                plusBtn.addEventListener('click', function() {
                    let currentVal = parseInt(input.value) || 1;
                    input.value = currentVal + 1;
                    input.dispatchEvent(new Event('change'));
                });
            }
        }

        input.addEventListener('change', async function() {
            const id = this.dataset.id;
            const quantity = parseInt(this.value);
            
            if (quantity < 1) {
                this.value = 1;
                return;
            }
            
            try {
                const res = await fetch(`/gio-hang/update/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ quantity })
                });
                
                const data = await res.json();
                
                if (data.success) {
                    const row = this.closest('tr');
                    const price = parseFloat(row.querySelector('.price').dataset.price);
                    const subtotal = price * quantity;
                    
                    row.querySelector('.subtotal').textContent = formatPrice(subtotal);
                    updateTotal();
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Có lỗi xảy ra khi cập nhật số lượng!', 'error');
            }
        });
    });
    
    // Xóa sản phẩm
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', async function() {
            // Instant removal per user request - no confirmation
            const id = this.dataset.id;
            
            try {
                const res = await fetch(`/gio-hang/remove/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await res.json();
                
                if (data.success) {
                    const row = this.closest('tr');
                    row.remove();
                    updateTotal();
                    showToast('Đã xóa sản phẩm khỏi giỏ hàng');
                    
                    if (document.querySelectorAll('tbody tr').length === 0) {
                        setTimeout(() => location.reload(), 1000);
                    }
                } else {
                    showToast('Không thể xóa sản phẩm!', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Có lỗi xảy ra khi xóa sản phẩm!', 'error');
            }
        });
    });
    
    // Cập nhật tổng
    function updateTotal() {
        let total = 0;

        document.querySelectorAll('tbody tr').forEach(row => {
            const priceEl = row.querySelector('.price');
            const qtyEl = row.querySelector('.quantity');
            if (!priceEl || !qtyEl) return;

            // Lấy giá từ data-price
            let price = parseFloat(priceEl.dataset.price);
            console.log('Raw price from data-price:', price);  
            
            const priceStr = price.toString().replace('.0', ''); 
            if (price && priceStr.length >= 8 && price % 100 === 0) {
                price = price / 100;
                console.log('Divided price:', price);
            }
            
            if (!price || isNaN(price)) {
                const cleanText = priceEl.textContent.replace(/[^\d]/g, '');
                price = Number(cleanText) || 0;
                const textStr = price.toString().replace('.0', '');
                if (price && textStr.length >= 8 && price % 100 === 0) {
                    price = price / 100;
                }
                console.log('Fallback price from text:', price); 
            }

            const qty = parseInt(qtyEl.value) || 1;
            console.log('Price * Qty:', price, '*', qty, '=', price * qty);

            total += price * qty;
        });

        console.log('Final total:', total);

        // Cập nhật tổng
        const subtotalEl = document.getElementById('summary-subtotal');
        if (subtotalEl) subtotalEl.textContent = formatPrice(total);
        
        const totalEl = document.getElementById('summary-total-price');
        if (totalEl) totalEl.textContent = formatPrice(total);
    }

    // Format tiền đồng nhất hiển thị dot phân cách ngàn
    function formatPrice(number) {
        const formatted = Math.round(number).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        return formatted + ' ₫';
    }

    // Gọi updateTotal ban đầu
    updateTotal();
});



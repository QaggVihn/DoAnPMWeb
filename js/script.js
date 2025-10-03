// script.js - Xử lý confirm xóa
function xacnhanXoa(tenSanPham) {
    var thongDieu = 'Bạn có chắc chắn muốn xóa sản phẩm "' + tenSanPham + '"?';
    return confirm(thongDieu);
}

function xacnhanXoaChung() {
    return confirm('Xác nhận xóa sản phẩm?');
}

// Xử lý form thêm giỏ AJAX
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý tất cả form thêm vào giỏ
    document.querySelectorAll('.them-gio-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Ngăn submit mặc định
            
            const formData = new FormData(this);
            fetch('themvaogio.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const toastEl = document.getElementById('liveToast');
                const toast = new bootstrap.Toast(toastEl);
                const toastBody = toastEl.querySelector('.toast-body');
                const toastHeader = toastEl.querySelector('.toast-header strong');
                
                toastBody.innerText = data.message;
                if (data.success) {
                    toastHeader.innerText = 'Thành công';
                    toastEl.classList.remove('bg-danger');
                    toastEl.classList.add('bg-success');
                    toast.show();
                    updateCartCount();
                } else {
                    toastHeader.innerText = 'Lỗi';
                    toastEl.classList.remove('bg-success');
                    toastEl.classList.add('bg-danger');
                    toast.show();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi thêm vào giỏ hàng!');
            });
        });
    });

    // Sửa lỗi click link trong carousel (force navigation)
    // const carouselLinks = document.querySelectorAll('#productCarousel .btn');
    // carouselLinks.forEach(link => {
    //     link.addEventListener('click', function(e) {
    //         e.stopPropagation(); // Ngăn carousel capture click
    //     });
    // });
    
    // Cập nhật số lượng giỏ hàng khi tải trang
    updateCartCount();
});

// Hàm cập nhật số lượng giỏ
function updateCartCount() {
    fetch('get_cart_count.php')
        .then(response => response.text())
        .then(count => {
            document.querySelectorAll('.cart-count').forEach(el => {
                el.innerText = count;
            });
        })
        .catch(error => console.error('Error updating cart count:', error));
}
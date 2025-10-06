<?php
function is_active($page_name, $current_page) {
    if ($page_name === $current_page) {
        return 'active';
    }
    return '';
}
?>


<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><img src="images/favicon.png" alt="Logo" class="brand-logo me2">Tạp hoá của Quang</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"> <a class="nav-link <?php echo is_active('index.php', $current_page); ?>" href="index.php"> <i class="fas fa-home me-1"></i> Trang chủ</a></li>
                <li class="nav-item"> <a class="nav-link <?php echo is_active('muahang.php', $current_page); ?>" href="muahang.php"> <i class="fas fa-shopping-cart me-1"></i> Mua hàng</a></li>
                <li class="nav-item"> <a class="nav-link <?php echo is_active('lienhe.php', $current_page); ?>" href="lienhe.php"> <i class="fas fa-envelope me-1"></i> Liên hệ </a></li>
            </ul>
            <form class="d-flex search-form" method="GET" action="muahang.php">
                <input class="form-control me-2" type="search" name="search" placeholder="Tìm kiếm sản phẩm..." aria-label="Search">
                <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
            </form>
            <ul class="navbar-nav ms-3">
                <li class="nav-item"><a class="nav-link <?php echo is_active('giohang.php', $current_page); ?>"  href="giohang.php"><i class="fas fa-shopping-cart me-1"></i>Giỏ hàng <span class="badge bg-danger cart-count">0</span></a></li>
                <?php if (isset($_SESSION['nguoidung_id'])): ?>
                    <?php if (isset($_SESSION['nguoidung_vaitro']) && $_SESSION['nguoidung_vaitro'] == 'quantri'): ?>
                        <li class="nav-item"><a class="nav-link <?php echo is_active('quantri.php', $current_page); ?>" href="quantri.php"><i class="fas fa-cog me-1"></i>Quản trị</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="dangxuat.php"><i class="fas fa-sign-out-alt me-1"></i>Đăng xuất</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link <?php echo is_active('dangnhap.php', $current_page); ?>" href="dangnhap.php"><i class="fas fa-sign-in-alt me-1"></i>Đăng nhập</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo is_active('dangky.php', $current_page); ?>" href="dangky.php"><i class="fas fa-user-plus me-1"></i>Đăng ký</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
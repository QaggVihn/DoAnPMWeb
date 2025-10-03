<?php
session_start();
include 'db.php';
if (!isset($_SESSION['giohang'])) {
    $_SESSION['giohang'] = [];
}

if (isset($_GET['xoa'])) {
    $sanpham_id = $_GET['xoa'];
    unset($_SESSION['giohang'][$sanpham_id]);
}

$success = false;
if (isset($_POST['dathang']) && isset($_SESSION['nguoidung_id'])) {
    foreach ($_SESSION['giohang'] as $sanpham_id => $soluong) {
        $truyvan_sp = $ketnoi->prepare("SELECT price, discount FROM products WHERE id = ?");
        $truyvan_sp->execute([$sanpham_id]);
        $sanpham = $truyvan_sp->fetch(PDO::FETCH_ASSOC);
        $discount = $sanpham['discount'] ?? 0;
        $sale_price = $sanpham['price'] * (1 - $discount / 100);
        $tonggia = $sale_price * $soluong;  //tính giá sau giảm

        $truyvan = $ketnoi->prepare("INSERT INTO orders (user_id, product_id, quantity, total_price, status) VALUES (?, ?, ?, ?, 'choxuly')");
        $truyvan->execute([$_SESSION['nguoidung_id'], $sanpham_id, $soluong, $tonggia]);
    }
    $_SESSION['giohang'] = [];
    $success = true;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="giohang">
        <?php 
        $current_page = 'giohang.php';
        include 'navbar.php'; 
        ?>

        <div class="container mt-4">
            <h1 class="text-center mb-4"><i class="fas fa-shopping-basket me-2"></i>Giỏ Hàng</h1>
            <?php if ($success): ?>
                <div class="alert alert-success text-center"><i class="fas fa-check-circle me-2"></i>Đặt hàng thành công!</div>
            <?php endif; ?>
            <?php if (empty($_SESSION['giohang'])): ?>
                <div class="alert alert-info text-center"><i class="fas fa-info-circle me-2"></i>Giỏ hàng trống. <a href="muahang.php">Mua sắm ngay!</a></div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><i class="fas fa-image me-2"></i>Sản phẩm</th>
                                <th><i class="fas fa-sort-numeric-down me-2"></i>Số lượng</th>
                                <th><i class="fas fa-dollar-sign me-2"></i>Giá</th>
                                <th><i class="fas fa-calculator me-2"></i>Tổng</th>
                                <th><i class="fas fa-trash me-2"></i>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $tongtien = 0;
                            foreach ($_SESSION['giohang'] as $sanpham_id => $soluong) {
                                $truyvan = $ketnoi->prepare("SELECT * FROM products WHERE id = ?");
                                $truyvan->execute([$sanpham_id]);
                                $sanpham = $truyvan->fetch(PDO::FETCH_ASSOC);
                                $discount = $sanpham['discount'] ?? 0;
                                $sale_price = $sanpham['price'] * (1 - $discount / 100);
                                $tonggia = $sale_price * $soluong;  //tính giá sau giảm
                                $tongtien += $tonggia;
                                echo '<tr>';
                                echo '<td>' . $sanpham['name'] . '</td>';
                                echo '<td>' . $soluong . '</td>';
                                if ($discount > 0) {
                                    echo '<td><del>' . number_format($sanpham['price']) . '</del> ' . number_format($sale_price) . ' (-' . $discount . '%)</td>';
                                } else {
                                    echo '<td>' . number_format($sanpham['price']) . '</td>';
                                }
                                echo '<td>' . number_format($tonggia) . '</td>';
                                echo '<td><a href="giohang.php?xoa=' . $sanpham_id . '" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Xóa</a></td>';
                                echo '</tr>';
                            }
                            echo '<tr class="table-success"><td colspan="3"><strong>Tổng tiền:</strong></td><td colspan="2">' . number_format($tongtien) . ' VND</td></tr>';
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php if (isset($_SESSION['nguoidung_id'])): ?>
                    <form method="POST" class="text-center">
                        <button type="submit" name="dathang" class="btn btn-primary btn-lg"><i class="fas fa-credit-card me-2"></i>Đặt hàng</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="thanhtoan.php" class="btn btn-success btn-lg"><i class="fas fa-money-bill-wave me-2"></i>Thanh toán</a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning text-center">Vui lòng <a href="dangnhap.php">đăng nhập</a> để đặt hàng.</div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
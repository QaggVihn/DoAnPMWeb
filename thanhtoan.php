<?php
// thanhtoan.php - Xử lý thanh toán giả lập và cập nhật đơn hàng
session_start();
include 'db.php';

if (!isset($_SESSION['nguoidung_id'])) {
    header("Location: dangnhap.php");
    exit();
}

$success = false;
$message = '';

// Giả lập thanh toán: Ở thực tế, tích hợp API thanh toán (VNPay, Momo, etc.)
// Ở đây, giả sử thanh toán thành công nếu POST['thanhtoan']
if (isset($_POST['thanhtoan'])) {
    // Lấy order mới nhất của user (giả sử sau đặt hàng từ giohang.php)
    $truyvan_order = $ketnoi->prepare("SELECT * FROM orders WHERE user_id = ? AND status = 'choxuly' ORDER BY created_at DESC LIMIT 1");
    $truyvan_order->execute([$_SESSION['nguoidung_id']]);
    $order = $truyvan_order->fetch(PDO::FETCH_ASSOC);

    if ($order) {
        // Cập nhật status thành 'hoanthanh'
        $truyvan_update = $ketnoi->prepare("UPDATE orders SET status = 'hoanthanh' WHERE id = ?");
        $truyvan_update->execute([$order['id']]);
        
        // Gửi thông báo đến admin (giả lập: có thể gửi email thực tế)
        // Ở đây, chỉ log hoặc hiển thị, thực tế dùng mail()
        // mail('admin@email.com', 'Đơn hàng mới', 'Đơn hàng ID: ' . $order['id'] . ' đã thanh toán.');
        
        $success = true;
        $message = 'Thanh toán thành công! Đơn hàng đã được gửi đến admin.';
    } else {
        $message = 'Không tìm thấy đơn hàng chờ xử lý.';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h1 class="text-center mb-4"><i class="fas fa-credit-card me-2"></i>Thanh Toán</h1>
        <?php if ($success): ?>
            <div class="alert alert-success text-center"><?php echo $message; ?></div>
        <?php else: ?>
            <div class="alert alert-info text-center"><?php echo $message ? $message : 'Giả lập thanh toán. Nhấn nút để hoàn tất.'; ?></div>
            <form method="POST" class="text-center">
                <button type="submit" name="thanhtoan" class="btn btn-success btn-lg"><i class="fas fa-money-check me-2"></i>Thanh Toán Ngay</button>
            </form>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
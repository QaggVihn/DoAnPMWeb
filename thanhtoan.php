<?php
session_start();
include 'db.php';

// Giả lập thanh toán thành công, xóa giỏ
if (isset($_SESSION['giohang'])) {
    // định lưu vào csdl nhưng mà thôi để sau thêm
    $_SESSION['giohang'] = [];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán Thành Công</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-success text-center">
            <i class="fas fa-check-circle me-2"></i>Thanh toán thành công! Cảm ơn bạn đã mua hàng.
            <br>
            <a href="index.php" class="btn btn-primary mt-3">Quay về trang chủ</a>
        </div>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
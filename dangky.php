<?php
session_start();
include 'db.php';
$success = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tendangnhap = $_POST['tendangnhap'];
    $matkhau = password_hash($_POST['matkhau'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $vaitro = 'khachhang';

    $truyvan = $ketnoi->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
    $truyvan->execute([$tendangnhap, $matkhau, $email, $vaitro]);
    $success = true;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="dangnhap-dangky">
        <?php 
        $current_page = 'dangky.php';
        include 'navbar.php'; 
        ?>

        <div class="container mt-4">
            <h1 class="text-center mb-4"><i class="fas fa-user-plus me-2"></i>Đăng Ký</h1>
            <?php if ($success): ?>
                <div class="alert alert-success text-center"><i class="fas fa-check-circle me-2"></i>Đăng ký thành công! <a href="dangnhap.php">Đăng nhập ngay</a></div>
            <?php else: ?>
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-user me-2"></i>Tên đăng nhập</label>
                                <input type="text" name="tendangnhap" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-lock me-2"></i>Mật khẩu</label>
                                <input type="password" name="matkhau" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-envelope me-2"></i>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-2"></i>Đăng ký</button>
                        </form>
                        <p class="text-center mt-3">Đã có tài khoản? <a href="dangnhap.php">Đăng nhập</a></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
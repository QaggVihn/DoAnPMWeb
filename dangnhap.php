<?php
session_start();
include 'db.php';
$loi = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tendangnhap = $_POST['tendangnhap'];
    $matkhau = $_POST['matkhau'];

    $truyvan = $ketnoi->prepare("SELECT * FROM users WHERE username = ?");
    $truyvan->execute([$tendangnhap]);
    $nguoidung = $truyvan->fetch(PDO::FETCH_ASSOC);

    if ($nguoidung && password_verify($matkhau, $nguoidung['password'])) {
        $_SESSION['nguoidung_id'] = $nguoidung['id'];
        $_SESSION['nguoidung_vaitro'] = $nguoidung['role'];
        header("Location: index.php");
    } else {
        $loi = 'Sai tên đăng nhập hoặc mật khẩu.';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="dangnhap-dangky">
        <?php 
        $current_page = 'dangnhap.php';
        include 'navbar.php'; 
        ?>

        <div class="container mt-4">
            <h1 class="text-center mb-4"><i class="fas fa-sign-in-alt me-2"></i>Đăng Nhập</h1>
            <?php if ($loi): ?>
                <div class="alert alert-danger text-center"><i class="fas fa-exclamation-triangle me-2"></i><?php echo $loi; ?></div>
            <?php endif; ?>
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
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-arrow-right me-2"></i>Đăng nhập</button>
                    </form>
                    <p class="text-center mt-3">Chưa có tài khoản? <a href="dangky.php">Đăng ký ngay</a></p>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
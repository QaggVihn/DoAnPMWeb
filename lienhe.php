<?php
session_start();
include 'db.php';
$success = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten = $_POST['ten'];
    $email = $_POST['email'];
    $tinnhan = $_POST['tinnhan'];
    $success = true;  // báo thành công giả
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên Hệ</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php 
    $current_page = 'lienhe.php';
    include 'navbar.php' 
    ?>

    <div class="container mt-4">
        <h1 class="text-center mb-4"><i class="fas fa-headset me-2"></i>Liên Hệ Với Chúng Tôi</h1>
        <?php if ($success): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>Tin nhắn đã gửi thành công! Chúng tôi sẽ liên hệ sớm.</div>
        <?php endif; ?>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-user me-2"></i>Tên của bạn</label>
                        <input type="text" name="ten" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-envelope me-2"></i>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-comment me-2"></i>Tin nhắn</label>
                        <textarea name="tinnhan" class="form-control" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-paper-plane me-2"></i>Gửi</button>
                </form>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
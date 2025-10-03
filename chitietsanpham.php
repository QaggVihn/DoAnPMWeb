<?php
session_start();
include 'db.php';

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['giohang'])) {
    $_SESSION['giohang'] = [];
}

$sanpham = null;
$sanpham_khac = [];  // Khởi tạo mảng sản phẩm khác

if (isset($_GET['id'])) {
    $sanpham_id = $_GET['id'];
    $truyvan = $ketnoi->prepare("SELECT * FROM products WHERE id = ?");
    $truyvan->execute([$sanpham_id]);
    $sanpham = $truyvan->fetch(PDO::FETCH_ASSOC);
    
    // Lấy sản phẩm khác bất kỳ
    if ($sanpham) {
        $truyvan_khac = $ketnoi->prepare("SELECT * FROM products WHERE id != ? ORDER BY RAND() LIMIT 4");
        $truyvan_khac->execute([$sanpham_id]);
        $sanpham_khac = $truyvan_khac->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Sản Phẩm</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <?php if ($sanpham): ?>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <img src="images/<?php echo $sanpham['image']; ?>" class="card-img-top detail-img" alt="<?php echo $sanpham['name']; ?>">
                        <div class="card-body">
                            <h2 class="card-title"><?php echo $sanpham['name']; ?></h2>
                            <p class="card-text"><i class="fas fa-align-left me-2"></i>Mô tả: <?php echo $sanpham['description']; ?></p>
                            <?php
                            $discount = $sanpham['discount'] ?? 0;
                            $sale_price = $sanpham['price'] * (1 - $discount / 100);
                            if ($discount > 0) {
                                echo '<p class="card-text"><i class="fas fa-tag me-2"></i>Giá: <del>' . number_format($sanpham['price']) . ' VND</del> <span class="text-danger">' . number_format($sale_price) . ' VND (-' . $discount . '%)</span></p>';
                            } else {
                                echo '<p class="card-text"><i class="fas fa-tag me-2"></i>Giá: ' . number_format($sanpham['price']) . ' VND</p>';
                            }
                            ?>
                            <p class="card-text"><i class="fas fa-warehouse me-2"></i>Tồn kho: <?php echo $sanpham['stock']; ?></p>
                            <form class="them-gio-form">
                                <input type="hidden" name="sanpham_id" value="<?php echo $sanpham['id']; ?>">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="number" name="soluong" value="1" min="1" class="form-control">
                                    </div>
                                    <div class="col-md-8">
                                        <button type="submit" name="themvaogio" class="btn btn-success w-100"><i class="fas fa-cart-plus me-2"></i>Thêm vào giỏ</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Phần sản phẩm khác -->
            <?php if (!empty($sanpham_khac)): ?>
                <div class="row mt-5">
                    <div class="col-12">
                        <h3 class="text-center mb-4"><i class="fas fa-th-large me-2"></i>Sản Phẩm Khác</h3>
                    </div>
                    <?php foreach ($sanpham_khac as $sp_khac): ?>
                        <?php
                        $discount_khac = $sp_khac['discount'] ?? 0;
                        $sale_price_khac = $sp_khac['price'] * (1 - $discount_khac / 100);
                        $badge_khac = ($discount_khac > 0) ? '<span class="badge badge-sale">Sale ' . $discount_khac . '%</span>' : '';
                        ?>
                        <div class="col-md-3 mb-4">
                            <div class="card position-relative h-100">
                                <div class="product-img-box"><img src="images/<?php echo $sp_khac['image']; ?>" class="img-fluid product-thumbnail" alt="<?php echo $sp_khac['name']; ?>"></div>
                                <?php echo $badge_khac; ?>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo $sp_khac['name']; ?></h5>
                                    <?php if ($discount_khac > 0): ?>
                                        <p class="card-text"><del><?php echo number_format($sp_khac['price']); ?> VND</del> <span class="text-danger"><?php echo number_format($sale_price_khac); ?> VND</span></p>
                                    <?php else: ?>
                                        <p class="card-text"><?php echo number_format($sp_khac['price']); ?> VND</p>
                                    <?php endif; ?>
                                    <a href="chitietsanpham.php?id=<?php echo $sp_khac['id']; ?>" class="btn btn-outline-primary mt-auto">Xem Chi Tiết</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-warning text-center">Sản phẩm không tồn tại.</div>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
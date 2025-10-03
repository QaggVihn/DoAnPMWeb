<?php
session_start();
include 'db.php';
if (!isset($_SESSION['nguoidung_id']) || $_SESSION['nguoidung_vaitro'] != 'quantri') {
    header("Location: dangnhap.php");
    exit();
}

// Xử lý thêm danh mục
if (isset($_POST['themdanhmuc'])) {
    $name = $_POST['name'];
    $stmt = $ketnoi->prepare("INSERT INTO categories (name) VALUES (?)");
    if ($stmt->execute([$name])) {
        $thongbao = '<div class="alert alert-success">Thêm danh mục thành công!</div>';
    } else {
        $thongbao = '<div class="alert alert-danger">Lỗi khi thêm danh mục!</div>';
    }
}

// Xử lý sửa danh mục
if (isset($_POST['suadanhmuc'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $stmt = $ketnoi->prepare("UPDATE categories SET name = ? WHERE id = ?");
    if ($stmt->execute([$name, $id])) {
        $thongbao = '<div class="alert alert-success">Sửa danh mục thành công!</div>';
    } else {
        $thongbao = '<div class="alert alert-danger">Lỗi khi sửa danh mục!</div>';
    }
}

// Xử lý xóa danh mục
if (isset($_GET['xoadanhmuc'])) {
    $id = $_GET['xoadanhmuc'];
    $stmt = $ketnoi->prepare("DELETE FROM categories WHERE id = ?");
    if ($stmt->execute([$id])) {
        $thongbao = '<div class="alert alert-success">Xóa danh mục thành công!</div>';
    } else {
        $thongbao = '<div class="alert alert-danger">Lỗi khi xóa danh mục! (Có thể có sản phẩm liên kết)</div>';
    }
}

// Xử lý thêm sản phẩm
if (isset($_POST['themsanpham'])) {
    $ten = $_POST['ten'];
    $mota = $_POST['mota'];
    $gia = $_POST['gia'];
    $tonkho = $_POST['tonkho'];
    $anh = $_POST['anh'];
    $category_id = $_POST['category_id'];
    $discount = isset($_POST['discount']) ? $_POST['discount'] : 0;  // Thêm discount

    $stmt = $ketnoi->prepare("INSERT INTO products (name, description, price, stock, image, category_id, discount) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$ten, $mota, $gia, $tonkho, $anh, $category_id, $discount])) {
        $thongbao_sp = '<div class="alert alert-success">Thêm sản phẩm thành công!</div>';
    } else {
        $thongbao_sp = '<div class="alert alert-danger">Lỗi khi thêm sản phẩm!</div>';
    }
}

// Xử lý sửa sản phẩm
if (isset($_POST['suasanpham'])) {
    $id = $_POST['id'];
    $ten = $_POST['ten'];
    $mota = $_POST['mota'];
    $gia = $_POST['gia'];
    $tonkho = $_POST['tonkho'];
    $anh = $_POST['anh'];
    $category_id = $_POST['category_id'];
    $discount = isset($_POST['discount']) ? $_POST['discount'] : 0;  // Thêm discount

    $stmt = $ketnoi->prepare("UPDATE products SET name=?, description=?, price=?, stock=?, image=?, category_id=?, discount=? WHERE id=?");
    if ($stmt->execute([$ten, $mota, $gia, $tonkho, $anh, $category_id, $discount, $id])) {
        $thongbao_sp = '<div class="alert alert-success">Sửa sản phẩm thành công!</div>';
    } else {
        $thongbao_sp = '<div class="alert alert-danger">Lỗi khi sửa sản phẩm!</div>';
    }
}

// Xử lý xóa sản phẩm
if (isset($_GET['xoasanpham'])) {
    $id = $_GET['xoasanpham'];
    $stmt = $ketnoi->prepare("DELETE FROM products WHERE id=?");
    if ($stmt->execute([$id])) {
        $thongbao_sp = '<div class="alert alert-success">Xóa sản phẩm thành công!</div>';
    } else {
        $thongbao_sp = '<div class="alert alert-danger">Lỗi khi xóa sản phẩm!</div>';
    }
}

// Lấy danh sách categories và products
$stmt_cat = $ketnoi->prepare("SELECT * FROM categories");
$stmt_cat->execute();
$categories = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);

$stmt_prod = $ketnoi->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id");
$stmt_prod->execute();
$products = $stmt_prod->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php 
    $current_page = 'quantri.php';
    include 'navbar.php'; 
    ?>

    <div class="container mt-4">
        <h1 class="text-center mb-4"><i class="fas fa-tools me-2"></i>Quản Lý Hệ Thống</h1>

        <?php if (isset($thongbao)) echo $thongbao; ?>
        <?php if (isset($thongbao_sp)) echo $thongbao_sp; ?>

        <!-- Phần Quản Lý Danh Mục -->
        <div class="card mb-5">
            <div class="card-header"><h2><i class="fas fa-tags me-2"></i>Quản Lý Danh Mục</h2></div>
            <div class="card-body">
                <!-- Form thêm danh mục -->
                <h4 class="mb-3">Thêm Danh Mục</h4>
                <form method="POST" class="row g-3 mb-4">
                    <div class="col-md-6">
                        <input type="text" name="name" class="form-control" placeholder="Tên danh mục (VD: Sách)" required>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" name="themdanhmuc" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Thêm</button>
                    </div>
                </form>

                <!-- Danh sách danh mục -->
                <div class="row">
                    <?php foreach ($categories as $cat): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card no-hover">
                                <div class="card-body">
                                    <h5><?php echo htmlspecialchars($cat['name']); ?></h5>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
                                        <input type="text" name="name" value="<?php echo htmlspecialchars($cat['name']); ?>" class="form-control mb-2" required>
                                        <button type="submit" name="suadanhmuc" class="btn btn-primary btn-sm"><i class="fas fa-edit me-2"></i>Sửa</button>
                                    </form>
                                    <a href="quantri.php?xoadanhmuc=<?php echo $cat['id']; ?>" class="btn btn-danger btn-sm mt-1" onclick="return xacnhanXoa('<?php echo $cat['name']; ?>')"><i class="fas fa-trash me-2"></i>Xóa</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Phần Quản Lý Sản Phẩm -->
        <div class="card">
            <div class="card-header"><h2><i class="fas fa-box me-2"></i>Quản Lý Sản Phẩm</h2></div>
            <div class="card-body">
                <!-- Form thêm sản phẩm -->
                <h4 class="mb-3">Thêm Sản Phẩm</h4>
                <form method="POST" class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Tên sản phẩm</label>
                        <input type="text" name="ten" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Danh mục</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Chọn danh mục</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Mô tả</label>
                        <textarea name="mota" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Giá</label>
                        <input type="number" name="gia" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tồn kho</label>
                        <input type="number" name="tonkho" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Giảm giá (%)</label>
                        <input type="number" name="discount" class="form-control" min="0" max="100" value="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tên file ảnh</label>
                        <input type="text" name="anh" class="form-control" placeholder="VD: sanpham.jpg">
                    </div>
                    <div class="col-12">
                        <button type="submit" name="themsanpham" class="btn btn-primary"><i class="fas fa-save me-2"></i>Thêm</button>
                    </div>
                </form>

                <!-- Danh sách sản phẩm -->
                <h4>Danh Sách Sản Phẩm</h4>
                <div class="row">
                    <?php foreach ($products as $prod): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card no-hover position-relative">
                                <img src="images/<?php echo $prod['image']; ?>" class="card-img-top" alt="<?php echo $prod['name']; ?>" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5><?php echo htmlspecialchars($prod['name']); ?></h5>
                                    <p class="text-muted">Danh mục: <?php echo $prod['category_name'] ?? 'Không có'; ?></p>
                                    <p>Giá: <?php echo number_format($prod['price']); ?> VND</p>
                                    <p>Giảm giá: <?php echo $prod['discount']; ?>%</p>
                                    <button type="button" class="btn btn-primary btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $prod['id']; ?>"><i class="fas fa-edit me-2"></i>Sửa</button>
                                    <a href="quantri.php?xoasanpham=<?php echo $prod['id']; ?>" class="btn btn-danger btn-sm" onclick="return xacnhanXoa('<?php echo $prod['name']; ?>')"><i class="fas fa-trash me-2"></i>Xóa</a>
                                </div>
                            </div>

                            <!-- Modal sửa sản phẩm -->
                            <div class="modal fade no-hover" id="editModal<?php echo $prod['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Sửa Sản Phẩm: <?php echo htmlspecialchars($prod['name']); ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?php echo $prod['id']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">Tên</label>
                                                    <input type="text" name="ten" value="<?php echo htmlspecialchars($prod['name']); ?>" class="form-control" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Danh mục</label>
                                                    <select name="category_id" class="form-select" required>
                                                        <option value="">Chọn danh mục</option>
                                                        <?php foreach ($categories as $cat): ?>
                                                            <option value="<?php echo $cat['id']; ?>" <?php echo ($prod['category_id'] == $cat['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['name']); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Mô tả</label>
                                                    <textarea name="mota" class="form-control" rows="2"><?php echo htmlspecialchars($prod['description']); ?></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Giá</label>
                                                    <input type="number" name="gia" value="<?php echo $prod['price']; ?>" class="form-control" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Tồn kho</label>
                                                    <input type="number" name="tonkho" value="<?php echo $prod['stock']; ?>" class="form-control" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Giảm giá (%)</label>
                                                    <input type="number" name="discount" value="<?php echo $prod['discount']; ?>" class="form-control" min="0" max="100">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Ảnh</label>
                                                    <input type="text" name="anh" value="<?php echo htmlspecialchars($prod['image']); ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                <button type="submit" name="suasanpham" class="btn btn-primary">Lưu</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="js/bootstrap.bundle.min.js" defer></script>
    <script src="js/script.js"></script>
    <!-- Code chống giật khi sửa sản phẩm -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modals = document.querySelectorAll('.modal');
            modals.forEach(function(modal) {
                modal.addEventListener('show.bs.modal', function() {
                    document.body.classList.add('modal-open');
                    document.querySelectorAll('.card').forEach(function(card) {
                        card.classList.add('no-hover-force');
                    });
                });
                modal.addEventListener('hidden.bs.modal', function() {
                    document.body.classList.remove('modal-open');
                    document.querySelectorAll('.card').forEach(function(card) {
                        card.classList.remove('no-hover-force');
                    });
                });
            });
        });
    </script>
</body>
</html>
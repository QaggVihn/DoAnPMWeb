<?php
// quantri.php updated with user management and order delete
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

    $stmt = $ketnoi->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, image = ?, category_id = ?, discount = ? WHERE id = ?");
    if ($stmt->execute([$ten, $mota, $gia, $tonkho, $anh, $category_id, $discount, $id])) {
        $thongbao_sp = '<div class="alert alert-success">Sửa sản phẩm thành công!</div>';
    } else {
        $thongbao_sp = '<div class="alert alert-danger">Lỗi khi sửa sản phẩm!</div>';
    }
}

// Xử lý xóa sản phẩm
if (isset($_GET['xoasanpham'])) {
    $id = $_GET['xoasanpham'];
    $stmt = $ketnoi->prepare("DELETE FROM products WHERE id = ?");
    if ($stmt->execute([$id])) {
        $thongbao_sp = '<div class="alert alert-success">Xóa sản phẩm thành công!</div>';
    } else {
        $thongbao_sp = '<div class="alert alert-danger">Lỗi khi xóa sản phẩm! (Có thể có đơn hàng liên kết)</div>';
    }
}

// Xử lý cập nhật status đơn hàng
if (isset($_POST['capnhatdonhang'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $stmt = $ketnoi->prepare("UPDATE orders SET status = ? WHERE id = ?");
    if ($stmt->execute([$status, $id])) {
        $thongbao_donhang = '<div class="alert alert-success">Cập nhật đơn hàng thành công!</div>';
    } else {
        $thongbao_donhang = '<div class="alert alert-danger">Lỗi khi cập nhật đơn hàng!</div>';
    }
}

// Xử lý xóa đơn hàng
if (isset($_GET['xoadonhang'])) {
    $id = $_GET['xoadonhang'];
    $stmt = $ketnoi->prepare("DELETE FROM orders WHERE id = ?");
    if ($stmt->execute([$id])) {
        $thongbao_donhang = '<div class="alert alert-success">Xóa đơn hàng thành công!</div>';
    } else {
        $thongbao_donhang = '<div class="alert alert-danger">Lỗi khi xóa đơn hàng!</div>';
    }
}

// Xử lý thêm người dùng
if (isset($_POST['themnguoidung'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hash mật khẩu
    $role = $_POST['role'];

    $stmt = $ketnoi->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$username, $email, $password, $role])) {
        $thongbao_user = '<div class="alert alert-success">Thêm người dùng thành công!</div>';
    } else {
        $thongbao_user = '<div class="alert alert-danger">Lỗi khi thêm người dùng! (Có thể username/email trùng)</div>';
    }
}

// Xử lý sửa người dùng
if (isset($_POST['suanguoidung'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password_sql = '';
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $password_sql = ", password = '$password'";
    }

    $stmt = $ketnoi->prepare("UPDATE users SET username = ?, email = ?, role = ? $password_sql WHERE id = ?");
    if ($stmt->execute([$username, $email, $role, $id])) {
        $thongbao_user = '<div class="alert alert-success">Sửa người dùng thành công!</div>';
    } else {
        $thongbao_user = '<div class="alert alert-danger">Lỗi khi sửa người dùng!</div>';
    }
}

// Xử lý xóa người dùng
if (isset($_GET['xoanguoidung'])) {
    $id = $_GET['xoanguoidung'];
    $stmt = $ketnoi->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt->execute([$id])) {
        $thongbao_user = '<div class="alert alert-success">Xóa người dùng thành công!</div>';
    } else {
        $thongbao_user = '<div class="alert alert-danger">Lỗi khi xóa người dùng! (Có thể có đơn hàng liên kết)</div>';
    }
}

// Lấy danh sách categories
$stmt_cat = $ketnoi->prepare("SELECT * FROM categories");
$stmt_cat->execute();
$categories = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách products
$stmt_prod = $ketnoi->prepare("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id");
$stmt_prod->execute();
$products = $stmt_prod->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách orders
$stmt_orders = $ketnoi->prepare("SELECT o.*, u.username AS user_name, p.name AS product_name FROM orders o LEFT JOIN users u ON o.user_id = u.id LEFT JOIN products p ON o.product_id = p.id ORDER BY o.created_at DESC");
$stmt_orders->execute();
$orders = $stmt_orders->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách users
$stmt_users = $ketnoi->prepare("SELECT * FROM users");
$stmt_users->execute();
$users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
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
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h1 class="text-center mb-4"><i class="fas fa-cogs me-2"></i>Quản Trị</h1>

        <!-- Quản lý Danh Mục -->
        <div class="card mb-5">
            <div class="card-header"><h2 class="mb-0"><i class="fas fa-tags me-2"></i>Quản Lý Danh Mục</h2></div>
            <div class="card-body">
                <?php if (isset($thongbao)) echo $thongbao; ?>
                <form method="POST" class="mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" name="name" class="form-control" placeholder="Tên danh mục" required>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" name="themdanhmuc" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Thêm Danh Mục</button>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <?php foreach ($categories as $cat): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($cat['name']); ?></h5>
                                    <button class="btn btn-warning btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editCategoryModal<?php echo $cat['id']; ?>"><i class="fas fa-edit"></i> Sửa</button>
                                    <a href="quantri.php?xoadanhmuc=<?php echo $cat['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xác nhận xóa danh mục?');"><i class="fas fa-trash"></i> Xóa</a>
                                </div>
                            </div>
                            <!-- Modal sửa danh mục -->
                            <div class="modal fade" id="editCategoryModal<?php echo $cat['id']; ?>" tabindex="-1" aria-labelledby="editCategoryLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editCategoryLabel">Sửa Danh Mục</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">Tên Danh Mục</label>
                                                    <input type="text" name="name" value="<?php echo htmlspecialchars($cat['name']); ?>" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                <button type="submit" name="suadanhmuc" class="btn btn-primary">Lưu</button>
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

        <!-- Quản lý Sản Phẩm -->
        <div class="card mb-5">
            <div class="card-header"><h2 class="mb-0"><i class="fas fa-box me-2"></i>Quản Lý Sản Phẩm</h2></div>
            <div class="card-body">
                <?php if (isset($thongbao_sp)) echo $thongbao_sp; ?>
                <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addProductModal"><i class="fas fa-plus me-2"></i>Thêm Sản Phẩm</button>
                <!-- Modal thêm sản phẩm -->
                <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addProductLabel">Thêm Sản Phẩm</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="POST">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Tên Sản Phẩm</label>
                                        <input type="text" name="ten" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Mô Tả</label>
                                        <textarea name="mota" class="form-control"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Giá</label>
                                        <input type="number" name="gia" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tồn Kho</label>
                                        <input type="number" name="tonkho" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Danh Mục</label>
                                        <select name="category_id" class="form-control">
                                            <?php foreach ($categories as $cat): ?>
                                                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Giảm giá (%)</label>
                                        <input type="number" name="discount" class="form-control" min="0" max="100">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Ảnh</label>
                                        <input type="text" name="anh" class="form-control" placeholder="ten_anh.jpg">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                    <button type="submit" name="themsanpham" class="btn btn-primary">Thêm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php foreach ($products as $prod): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card no-hover">
                                <img src="images/<?php echo $prod['image']; ?>" class="card-img-top" alt="<?php echo $prod['name']; ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $prod['name']; ?></h5>
                                    <p class="card-text">Giá: <?php echo number_format($prod['price']); ?> VND</p>
                                    <p class="card-text">Danh mục: <?php echo $prod['category_name'] ?? 'Không có'; ?></p>
                                    <p class="card-text">Giảm giá: <?php echo $prod['discount']; ?>%</p>
                                    <button class="btn btn-warning btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editProductModal<?php echo $prod['id']; ?>"><i class="fas fa-edit"></i> Sửa</button>
                                    <a href="quantri.php?xoasanpham=<?php echo $prod['id']; ?>" class="btn btn-danger btn-sm" onclick="return xacnhanXoaChung();"><i class="fas fa-trash"></i> Xóa</a>
                                </div>
                            </div>
                            <!-- Modal sửa sản phẩm -->
                            <div class="modal fade" id="editProductModal<?php echo $prod['id']; ?>" tabindex="-1" aria-labelledby="editProductLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editProductLabel">Sửa Sản Phẩm</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?php echo $prod['id']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">Tên Sản Phẩm</label>
                                                    <input type="text" name="ten" value="<?php echo htmlspecialchars($prod['name']); ?>" class="form-control" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Mô Tả</label>
                                                    <textarea name="mota" class="form-control"><?php echo htmlspecialchars($prod['description']); ?></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Giá</label>
                                                    <input type="number" name="gia" value="<?php echo $prod['price']; ?>" class="form-control" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Tồn Kho</label>
                                                    <input type="number" name="tonkho" value="<?php echo $prod['stock']; ?>" class="form-control" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Danh Mục</label>
                                                    <select name="category_id" class="form-control">
                                                        <?php foreach ($categories as $cat): ?>
                                                            <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $prod['category_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['name']); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
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

        <!-- Quản lý Đơn Hàng -->
        <div class="card mb-5">
            <div class="card-header"><h2 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Quản Lý Đơn Hàng</h2></div>
            <div class="card-body">
                <?php if (isset($thongbao_donhang)) echo $thongbao_donhang; ?>
                <?php if (empty($orders)): ?>
                    <div class="alert alert-info text-center">Chưa có đơn hàng nào.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Người Đặt</th>
                                    <th>Sản Phẩm</th>
                                    <th>Số Lượng</th>
                                    <th>Tổng Giá</th>
                                    <th>Trạng Thái</th>
                                    <th>Ngày Đặt</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?php echo $order['id']; ?></td>
                                        <td><?php echo $order['user_name']; ?></td>
                                        <td><?php echo $order['product_name']; ?></td>
                                        <td><?php echo $order['quantity']; ?></td>
                                        <td><?php echo number_format($order['total_price']); ?> VND</td>
                                        <td><?php echo $order['status']; ?></td>
                                        <td><?php echo $order['created_at']; ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editOrderModal<?php echo $order['id']; ?>"><i class="fas fa-edit"></i> Cập Nhật</button>
                                            <a href="quantri.php?xoadonhang=<?php echo $order['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xác nhận xóa đơn hàng?');"><i class="fas fa-trash"></i> Xóa</a>
                                        </td>
                                    </tr>
                                    <!-- Modal cập nhật đơn hàng -->
                                    <div class="modal fade" id="editOrderModal<?php echo $order['id']; ?>" tabindex="-1" aria-labelledby="editOrderLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editOrderLabel">Cập Nhật Đơn Hàng</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                                                        <div class="mb-3">
                                                            <label class="form-label">Trạng Thái</label>
                                                            <select name="status" class="form-control">
                                                                <option value="choxuly" <?php echo $order['status'] == 'choxuly' ? 'selected' : ''; ?>>Chờ Xử Lý</option>
                                                                <option value="hoanthanh" <?php echo $order['status'] == 'hoanthanh' ? 'selected' : ''; ?>>Hoàn Thành</option>
                                                                <option value="huy" <?php echo $order['status'] == 'huy' ? 'selected' : ''; ?>>Hủy</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                        <button type="submit" name="capnhatdonhang" class="btn btn-primary">Lưu</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quản lý Người Dùng -->
        <div class="card mb-5">
            <div class="card-header"><h2 class="mb-0"><i class="fas fa-users me-2"></i>Quản Lý Người Dùng</h2></div>
            <div class="card-body">
                <?php if (isset($thongbao_user)) echo $thongbao_user; ?>
                <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="fas fa-plus me-2"></i>Thêm Người Dùng</button>
                <!-- Modal thêm người dùng -->
                <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addUserLabel">Thêm Người Dùng</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="POST">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Username</label>
                                        <input type="text" name="username" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Mật Khẩu</label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Vai Trò</label>
                                        <select name="role" class="form-control">
                                            <option value="user">User</option>
                                            <option value="quantri">Admin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                    <button type="submit" name="themnguoidung" class="btn btn-primary">Thêm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php foreach ($users as $user): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card no-hover">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $user['username']; ?></h5>
                                    <p class="card-text">Email: <?php echo $user['email']; ?></p>
                                    <p class="card-text">Vai Trò: <?php echo $user['role']; ?></p>
                                    <button class="btn btn-warning btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editUserModal<?php echo $user['id']; ?>"><i class="fas fa-edit"></i> Sửa</button>
                                    <a href="quantri.php?xoanguoidung=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xác nhận xóa người dùng?');"><i class="fas fa-trash"></i> Xóa</a>
                                </div>
                            </div>
                            <!-- Modal sửa người dùng -->
                            <div class="modal fade" id="editUserModal<?php echo $user['id']; ?>" tabindex="-1" aria-labelledby="editUserLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editUserLabel">Sửa Người Dùng</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">Username</label>
                                                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" class="form-control" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="form-control" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Mật Khẩu Mới (Để trống nếu không đổi)</label>
                                                    <input type="password" name="password" class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Vai Trò</label>
                                                    <select name="role" class="form-control">
                                                        <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                                                        <option value="quantri" <?php echo $user['role'] == 'quantri' ? 'selected' : ''; ?>>Admin</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                <button type="submit" name="suanguoidung" class="btn btn-primary">Lưu</button>
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
    <!-- SỬA GIẬT: JS pause hover khi modal mở -->
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
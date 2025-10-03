<?php
error_reporting(E_ALL & ~E_NOTICE);  // Giảm warning, tránh output thô
session_start();
include 'db.php'; 

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['giohang'])) {
    $_SESSION['giohang'] = [];
}

// Logic tìm kiếm và lọc giá + category + sale
$ketqua_timkiem = [];
$tu_khoa = '';
$giatu = isset($_GET['giatu']) ? $_GET['giatu'] : 0;
$giaden = isset($_GET['giaden']) ? $_GET['giaden'] : 0;
$is_sale = isset($_GET['sale']) && $_GET['sale'] == 1;
$thongbao = '';
$where = "WHERE 1=1";
$params = [];

if (isset($_GET['search'])) {
    $tu_khoa = $_GET['search'];
    $where .= " AND (p.name LIKE ? OR p.description LIKE ?)";
    $params[] = "%$tu_khoa%";
    $params[] = "%$tu_khoa%";
}

// Lọc theo category_id nếu có
if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
    $where .= " AND p.category_id = ?";
    $params[] = $category_id;
}

// Lọc sản phẩm sale nếu ?sale=1
if ($is_sale) {
    $where .= " AND p.discount > 0";
}

if ($giatu > 0) {
    $where .= " AND p.price >= ?";
    $params[] = $giatu;
}
if ($giaden > 0) {
    $where .= " AND p.price <= ?";
    $params[] = $giaden;
}

try {
    $truyvan = $ketnoi->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id $where ORDER BY p.created_at DESC");
    $truyvan->execute($params);
    $ketqua_timkiem = $truyvan->fetchAll(PDO::FETCH_ASSOC);

    if (empty($ketqua_timkiem) && $tu_khoa) {
        $thongbao = "Không tìm thấy sản phẩm nào với từ khóa '$tu_khoa'. Thử từ khóa khác nhé!";
    }
} catch (PDOException $e) {
    $thongbao = "Lỗi SQL: " . $e->getMessage() . " (Kiểm tra query và dữ liệu DB)";
    error_log("Muahang.php SQL Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mua Hàng <?php echo $tu_khoa ? ' - Tìm kiếm: ' . htmlspecialchars($tu_khoa) : ''; ?></title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php 
    $current_page = 'muahang.php';
    include 'navbar.php'; 
    ?>

    <!-- Nội dung chính -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar danh mục -->
            <div class="col-lg-2 mb-4">
                <div class="categories">
                    <h5><i class="fas fa-tags me-2"></i>Danh Mục</h5>
                    <?php
                    // sản phẩm đang giảm giá
                    $sale_active = $is_sale ? 'active' : '';
                    echo '<a href="muahang.php?sale=1" class="cat-item ' . $sale_active . '"><i class="fas fa-tags me-2 text-danger"></i>Sản Phẩm Giảm Giá</a>';
                    ?>
                    <?php
                    try {
                        $stmt = $ketnoi->prepare("SELECT * FROM categories");
                        $stmt->execute();
                        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($categories as $cat) {
                            $active = (isset($_GET['category_id']) && $_GET['category_id'] == $cat['id']) ? 'active' : '';
                            // Kiểm tra nếu danh mục có sản phẩm giảm giá
                            $sale_check_stmt = $ketnoi->prepare("SELECT COUNT(*) FROM products WHERE category_id = ? AND discount > 0");
                            $sale_check_stmt->execute([$cat['id']]);
                            $has_sale = $sale_check_stmt->fetchColumn() > 0;
                            $sale_badge = $has_sale ? ' <span class="badge bg-danger">Sale</span>' : '';
                            echo '<a href="muahang.php?category_id=' . $cat['id'] . '" class="cat-item ' . $active . '"><i class="fas fa-book me-2"></i>' . htmlspecialchars($cat['name']) . $sale_badge . '</a>';
                        }
                    } catch (PDOException $e) {
                        echo '<p class="text-danger">Lỗi load danh mục: ' . $e->getMessage() . '</p>';
                    }
                    ?>
                </div>
                <!-- Bộ lọc giá -->
                <h5 class="mt-4"><i class="fas fa-filter me-2"></i>Lọc Giá</h5>
                <form method="GET" action="muahang.php">
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($tu_khoa); ?>">
                    <div class="mb-2">
                        <label>Giá từ:</label>
                        <input type="number" name="giatu" class="form-control" value="<?php echo $giatu; ?>">
                    </div>
                    <div class="mb-2">
                        <label>Giá đến:</label>
                        <input type="number" name="giaden" class="form-control" value="<?php echo $giaden; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Lọc</button>
                </form>
            </div>

            <!-- Kết quả tìm kiếm hoặc danh sách sản phẩm -->
            <div class="col-lg-9">
                <?php if ($thongbao): ?>
                    <div class="alert alert-info text-center mb-4"><i class="fas fa-search me-2"></i><?php echo $thongbao; ?></div>
                    <a href="muahang.php" class="btn btn-primary mb-4">Xem tất cả sản phẩm</a>
                <?php else: ?>
                    <h2 class="text-center mb-4"><?php echo $is_sale ? 'Sản Phẩm Giảm Giá' : ($tu_khoa ? 'Kết quả tìm kiếm: ' . htmlspecialchars($tu_khoa) : 'Danh Sách Sản Phẩm'); ?></h2>
                    <div class="row">
                        <?php
                        foreach ($ketqua_timkiem as $sanpham) {
                            $discount = $sanpham['discount'] ?? 0;
                            $sale_price = $sanpham['price'] * (1 - $discount / 100);
                            $badge = ($discount > 0) ? '<span class="badge badge-sale">Sale ' . $discount . '%</span>' : '';  // Dynamic badge
                            echo '<div class="col-md-4 mb-4">';
                            echo '<div class="card position-relative">';
                            echo '<div class="product-img-box"><img src="images/' . $sanpham['image'] . '" class="img-fluid product-thumbnail" alt="' . $sanpham['name'] . '"></div>';
                            echo $badge;
                            echo '<div class="card-body">';
                            echo '<h5 class="card-title">' . $sanpham['name'] . '</h5>';
                            echo '<p class="text-muted small">Danh mục: ' . ($sanpham['category_name'] ?? 'Không có') . '</p>';
                            if ($discount > 0) {
                                echo '<p class="card-text"><del>' . number_format($sanpham['price']) . ' VND</del> <span class="text-danger">' . number_format($sale_price) . ' VND (-' . $discount . '%)</span></p>';
                            } else {
                                echo '<p class="card-text">' . number_format($sanpham['price']) . ' VND</p>';
                            }
                            echo '<a href="chitietsanpham.php?id=' . $sanpham['id'] . '" class="btn btn-outline-primary">Chi tiết</a>';
                            echo '<form class="them-gio-form mt-2">';
                            echo '<input type="hidden" name="sanpham_id" value="' . $sanpham['id'] . '">';
                            echo '<input type="number" name="soluong" value="1" min="1" class="form-control mb-2">';
                            echo '<button type="submit" name="themvaogio" class="btn btn-success w-100">Thêm vào giỏ</button>';
                            echo '</form>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                        if (empty($ketqua_timkiem) && !$tu_khoa): ?>
                            <div class="alert alert-warning text-center">Chưa có sản phẩm nào. </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
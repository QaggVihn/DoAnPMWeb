<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['giohang'])) {
    $_SESSION['giohang'] = [];
}

if (isset($_POST['sanpham_id']) && isset($_POST['soluong'])) {
    $sanpham_id = $_POST['sanpham_id'];
    $soluong = intval($_POST['soluong']);
    
    // Kiểm tra tồn kho
    $truyvan = $ketnoi->prepare("SELECT stock, name FROM products WHERE id = ?");
    $truyvan->execute([$sanpham_id]);
    $sanpham = $truyvan->fetch(PDO::FETCH_ASSOC);
    
    if ($sanpham && $sanpham['stock'] >= $soluong) {
        // Thêm vào giỏ hoặc cộng dồn số lượng
        if (isset($_SESSION['giohang'][$sanpham_id])) {
            $_SESSION['giohang'][$sanpham_id] += $soluong;
        } else {
            $_SESSION['giohang'][$sanpham_id] = $soluong;
        }
        echo json_encode(['success' => true, 'message' => 'Đã thêm "' . $sanpham['name'] . '" vào giỏ hàng!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm hết hàng hoặc số lượng không đủ!']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ!']);
}
?>
<?php
$current_page = 'index.php';
include 'header.php' 
?>

<body>
    <?php include 'navbar.php' ?>

    <!-- Hero Section: Banner lớn với carousel -->
    <div id="heroCarousel" class="carousel slide carousel-fade mb-5" data-bs-ride="carousel">
        <div class="carousel-inner">
            <!-- Slide 1 -->
            <div class="carousel-item active">
                <img src="images/banner1.jpg" class="d-block w-100" alt="Banner 1" style="height: 500px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block text-center">
                    <h1 class="display-4 fw-bold text-white">Chào Mừng Đến Với Cửa Hàng Của Chúng Tôi!</h1>
                    <p class="lead text-white">Khám phá hàng ngàn sản phẩm chất lượng cao với giá tốt nhất.</p>
                    <a href="muahang.php" class="btn btn-primary btn-lg">Khám Phá Ngay</a>
                </div>
            </div>
            <!-- Slide 2 -->
            <div class="carousel-item">
                <img src="images/banner2.jpg" class="d-block w-100" alt="Banner 2" style="height: 500px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block text-center">
                    <h1 class="display-4 fw-bold text-white">Ưu Đãi Đặc Biệt Tháng 10!</h1>
                    <p class="lead text-white">Giảm giá lên đến 50% cho các sản phẩm hot.</p>
                    <a href="muahang.php?sale=1" class="btn btn-success btn-lg">Mua Ngay</a>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Categories - ĐỘNG TỪ DB -->
            <div class="col-lg-1 mb-4">

            </div>

            <!-- Danh sách sản phẩm nổi bật -->
            <div class="col-lg-10">
                <h2 class="text-center mb-4">Sản Phẩm Nổi Bật</h2>
                <?php
                $truyvan = $ketnoi->prepare("SELECT * FROM products LIMIT 6");
                $truyvan->execute();
                $danhsachsanpham = $truyvan->fetchAll(PDO::FETCH_ASSOC);
                if (empty($danhsachsanpham)) {
                    echo '<div class="alert alert-info text-center">Chưa có sản phẩm nổi bật. Hãy thêm sản phẩm qua <a href="quantri.php">quản trị</a> (đăng nhập admin)!</div>';
                } else {
                ?>
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        $active = 'active';
                        foreach (array_chunk($danhsachsanpham, 3) as $group) {
                            echo '<div class="carousel-item ' . $active . '">';
                            echo '<div class="row">';
                            foreach ($group as $sanpham) {
                                $discount = $sanpham['discount'] ?? 0;
                                $sale_price = $sanpham['price'] * (1 - $discount / 100);
                                $badge = ($discount > 0) ? '<span class="badge badge-sale">Sale ' . $discount . '%</span>' : '';  // Dynamic
                                echo '<div class="col-md-4 mb-4">';
                                echo '<div class="card position-relative">';
                                echo '<img src="images/' . $sanpham['image'] . '" class="card-img-top" alt="' . $sanpham['name'] . '">';
                                echo $badge;
                                echo '<div class="card-body text-center">';
                                echo '<h5 class="card-title">' . $sanpham['name'] . '</h5>';
                                if ($discount > 0) {
                                    echo '<p class="card-text"><del>' . number_format($sanpham['price']) . ' VND</del> <span class="text-danger">' . number_format($sale_price) . ' VND</span></p>';
                                } else {
                                    echo '<p class="card-text">' . number_format($sanpham['price']) . ' VND</p>';
                                }
                                echo '<a href="chitietsanpham.php?id=' . $sanpham['id'] . '" class="btn btn-outline-primary">Chi tiết</a>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                            echo '</div>';
                            echo '</div>';
                            $active = '';
                        }
                        ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                <?php } ?>

                <!-- Phần Sản Phẩm Mới  -->
                <h2 class="text-center mt-5 mb-4">Sản Phẩm Mới Nhất</h2>
                <div class="row">
                    <?php
                    $newProductsQuery = $ketnoi->prepare("SELECT * FROM products ORDER BY created_at DESC LIMIT 4");
                    $newProductsQuery->execute();
                    $newProducts = $newProductsQuery->fetchAll(PDO::FETCH_ASSOC);
                    if (!empty($newProducts)) {
                        foreach ($newProducts as $product) {
                            echo '<div class="col-md-3 mb-4">';
                            echo '<div class="card">';
                            echo '<img src="images/' . $product['image'] . '" class="card-img-top" alt="' . $product['name'] . '" style="height: 250px; object-fit: contain;">';
                            echo '<div class="card-body text-center">';
                            echo '<h5 class="card-title">' . $product['name'] . '</h5>';
                            echo '<p class="card-text">' . number_format($product['price']) . ' VND</p>';
                            echo '<a href="chitietsanpham.php?id=' . $product['id'] . '" class="btn btn-primary">Xem Chi Tiết</a>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="col-12 text-center">Chưa có sản phẩm mới.</div>';
                    }
                    ?>
                </div>

                <!-- Phần Sản Phẩm Giảm Giá -->
                <h2 class="text-center mt-5 mb-4">Sản Phẩm Giảm Giá</h2>
                <div class="row">
                    <?php
                    $saleProductsQuery = $ketnoi->prepare("SELECT * FROM products WHERE discount > 0 ORDER BY discount DESC LIMIT 4");
                    $saleProductsQuery->execute();
                    $saleProducts = $saleProductsQuery->fetchAll(PDO::FETCH_ASSOC);
                    if (!empty($saleProducts)) {
                        foreach ($saleProducts as $product) {
                            $salePrice = $product['price'] * (1 - $product['discount'] / 100);
                            echo '<div class="col-md-3 mb-4">';
                            echo '<div class="card">';
                            echo '<img src="images/' . $product['image'] . '" class="card-img-top" alt="' . $product['name'] . '" style="height: 250px; object-fit: contain;">';
                            echo '<div class="card-body text-center">';
                            echo '<h5 class="card-title">' . $product['name'] . '</h5>';
                            echo '<p class="card-text"><del>' . number_format($product['price']) . ' VND</del> <span class="text-danger">' . number_format($salePrice) . ' VND</span> (-' . $product['discount'] . '%)</p>';
                            echo '<a href="chitietsanpham.php?id=' . $product['id'] . '" class="btn btn-primary">Xem Chi Tiết</a>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="col-12 text-center">Chưa có sản phẩm giảm giá.</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>


    <?php include 'footer.php' ?>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
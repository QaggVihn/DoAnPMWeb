<?php
$ketnoi = null;
try {
    $ketnoi = new PDO("mysql:host=localhost;dbname=banhang", "root", "admin"); 
    $ketnoi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $loi) {
    echo "Kết nối thất bại: " . $loi->getMessage();
    die();
}
?>
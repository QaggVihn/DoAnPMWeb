<?php
session_start();
include 'db.php';
error_reporting(E_ALL); // Debug: Hiển thị lỗi nếu có
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
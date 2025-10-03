<?php
session_start();
echo isset($_SESSION['giohang']) ? array_sum($_SESSION['giohang']) : 0;
?>
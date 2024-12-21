<?php
session_start();
// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])) {
    // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
    header('Location: login.php');
    exit;
}

// Kiểm tra xem người dùng có phải là admin không
if ($_SESSION['role_id'] != 2) {
    // Nếu không phải admin, chuyển hướng về trang index.php
    header('Location: index.php');
    exit;
}

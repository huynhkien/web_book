<?php
require_once '../src/DBConnection.php';
require_once '../src/User.php';

$pdo = DBConnection::getConnection(); // Kết nối đến cơ sở dữ liệu
$userManager = new User($pdo);
$userManager->logout();

// // Lấy URL trang hiện tại trước khi logout
// $redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
// // Chuyển hướng người dùng về trang trước đó
// header('Location: ' . $redirect_url);
// Chuyển hướng người dùng về trang chủ
header('Location: ../index.php');
exit;

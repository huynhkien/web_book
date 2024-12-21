<?php
require_once '../src/DBConnection.php';
require_once '../src/User.php';

$pdo = DBConnection::getConnection();
$userManager = new User($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    // Lấy user_id từ form
    $user_id = $_POST["user_id"];
    $userManager->deleteUser($user_id);
    header("Location: show_user.php");
    exit();
} else {
    // Nếu không phải là phương thức POST hoặc không có tham số "delete" trong POST, chuyển hướng về trang chính
    header("Location: show_product.php");
    exit();
}

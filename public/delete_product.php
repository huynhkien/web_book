<?php
require_once '../src/DBConnection.php';
require_once '../src/Product.php';

$pdo = DBConnection::getConnection();
$productManager = new Product($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    // Lấy product_id từ form
    $product_id = $_POST["product_id"];
    $productManager->deleteProduct($product_id);
    header("Location: show_product.php");
    exit();
} else {
    // Nếu không phải là phương thức POST hoặc không có tham số "delete" trong POST, chuyển hướng về trang chính
    header("Location: show_product.php");
    exit();
}

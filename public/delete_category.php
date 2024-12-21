<?php
require_once '../src/DBConnection.php';
require_once '../src/Category.php';

$pdo = DBConnection::getConnection();
$categoryManager = new Category($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    // Lấy catalog_id từ form
    $catalog_id = $_POST["catalog_id"];
    $categoryManager->deleteCategory($catalog_id);
    header("Location: show_category.php");
    exit();
} else {
    // Nếu không phải là phương thức POST hoặc không có tham số "delete" trong POST, chuyển hướng về trang chính
    header("Location: show_category.php");
    exit();
}
?>

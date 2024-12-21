<?php
require_once '../src/DBConnection.php';
require_once '../src/Bill.php';

$pdo = DBConnection::getConnection();
$billManager = new Bill($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    // Lấy bill_id từ form
    $bill_id = $_POST["bill_id"];
    $billManager->deleteBill($bill_id);
    header("Location: show_bill.php");
    exit();
} else {
    // Nếu không phải là phương thức POST hoặc không có tham số "delete" trong POST, chuyển hướng về trang chính
    header("Location: show_bill.php");
    exit();
}

<?php
require_once '../src/DBConnection.php';
// require_once '../partials/check_admin.php';
require_once '../src/Bill.php';
require_once '../src/Product.php';
$pdo = DBConnection::getConnection(); // Kết nối đến cơ sở dữ liệu
$billManager = new Bill($pdo);
$productManager = new Product($pdo);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bill_id = $_POST["bill_id"];
    $status = $_POST["status"];

    if ($status === 'confirmed') {
        $billManager->confirmOrder($bill_id);
        $billDetails = $billManager->getBillDetail($bill_id);
        foreach ($billDetails as $billDetail) {
            // Cập nhật lại số lượng của sản phẩm trong bảng product(trừ ra)
            $product_id = $billDetail['product_id'];
            $bill_quantity = $billDetail['bill_quantity'];
            $productManager->updateProductQuantity($product_id, $bill_quantity);
        }
    } elseif ($status === 'delivered') {
        $billManager->markOrderDelivered($bill_id);
    } elseif ($status === 'cancelled') {
        $billManager->billReturn($bill_id);
        $billDetails = $billManager->getBillDetail($bill_id);
        foreach ($billDetails as $billDetail) {
            // Cập nhật lại số lượng của sản phẩm trong bảng product (cộng vào)
            $product_id = $billDetail['product_id'];
            $bill_quantity = $billDetail['bill_quantity'];
            $productManager->updateProductQuantityPlug($product_id, $bill_quantity);
        }
    } else {
        $billManager->notConfirmOrder($bill_id);
    }

    header("Location: show_bill.php");
    exit;
}

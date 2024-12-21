<?php
session_start();
require_once '../src/DBConnection.php';
require_once '../src/Product.php';

$pdo = DBConnection::getConnection(); // Kết nối đến cơ sở dữ liệu
$productManager = new Product($pdo);

// Khởi tạo biến tổng giá trị
$totalAmount = 0;

// Xóa sản phẩm và clearall
if (isset($_GET['action'])) {
    if ($_GET['action'] == "remove") {
        if(isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $key => $value) {
                if ($value['product_id'] == $_GET['product_id']) {
                    unset($_SESSION['cart'][$key]);
                }
            }
        }
    }
    if ($_GET['action'] == "clearall") {
        unset($_SESSION['cart']);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/main.css">

    <title>Trang chủ</title>
</head>

<body>
    <!-- Navbar -->
    <?php require '../partials/navbar.php'; ?>
    <!-- End Navbar -->

    <main>
        <div class="shop-form text-center m-5">
            <h2>GIỎ HÀNG</h2>
        </div>
        <div class="container cart_top">
            <table class='table align-middle mb-0 bg-white'>
                <thead class='bg-light'>
                    <tr class='text-center'>
                        <th scope='col' style='width: 30%;'>Ảnh</th>
                        <th scope='col' style='width: 60%;'>Sản phẩm</th>
                        <th scope='col' style='width: 10%;'>Đơn giá</th>
                        <th scope='col' style='width: 15%;'>SL</th>
                        <th scope='col' style='width: 10%;'>Tổng giá</th>
                        <th scope='col' style='width: 5%;'>Lựa chọn</th>
                    </tr>
                </thead>
                <tbody class='text-center'>
                    <?php
                    //cart
                    if(isset($_SESSION['cart'])){
                        foreach ($_SESSION['cart'] as $key => $item) {
                            $product_id = $item['product_id'];
                            $quantity = $item['quantity'];
                            $product = $productManager->getProductByProductId($product_id);

                            //update_quantity
                            if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit']) && isset($_POST['quantity'][$key])) {
                                $quantity = $_POST['quantity'][$key];
                                $_SESSION['cart'][$key]['quantity'] = $quantity;
                            }

                            $totalPrice = $product['productPrice'] * $quantity;
                            // Cộng vào tổng giá trị
                            $totalAmount += $totalPrice;
                    ?>
                    <tr>
                        <td><img src="<?php echo $product['img']; ?>" alt='Product Image'></td>
                        <td><?php echo $product['productName']; ?></td>
                        <td><?php echo number_format($product['productPrice'], 0, '.', '.'); ?></td>
                        <td>
                            <form class="d-flex" action="cartpage.php" method="post">
                                <input type='number' class='form-control quantity-input' style='width: 100px;' value='<?php echo $quantity; ?>' name="quantity[<?php echo $key; ?>]" data-product-id='<?php echo $product_id; ?>'>
                                <input type='submit' class='rounded' value='Update' name='submit'>
                            </form>
                        </td>
                        <td><?php echo number_format($totalPrice, 0, '.', '.'); ?></td>
                        <td>
                            <a href='?action=remove&product_id=<?php echo $product_id; ?>'>
                                <button class='btn btn-danger btn-block'>Xóa</button>
                            </a>
                        </td>
                    </tr>
                    <?php
                        }
                    }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan='4' class='text-start'><strong>Tổng tiền:</strong></td>
                        <td class='text-end'><strong><?php echo number_format($totalAmount, 0, '.', '.'); ?></strong></td>
                        <td>
                            <a href='?action=clearall'>
                                <button class='btn btn-warning btn-block'>Clear</button>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <form action="cart.php" method="post">
                                <button type="submit" id="dathang" name="add_order" class="btn btn-primary" style="width: 100%;">Đặt hàng</button>
                            </form>
                        </td>
                    </tr>
            </tfoot>

            </table>
        </div>
    </main>

    <!-- Footer -->
    <?php require '../partials/footer.php'; ?>
    <!-- End Footer -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="../assets/js/main.js"></script>
</body>

</html>

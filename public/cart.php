<?php
session_start();
require_once '../src/DBConnection.php';

require_once '../src/Product.php';
require_once '../src/User.php';
require_once '../src/Bill.php';
require_once '../mail/sendmail.php';
require_once '../partials/email_content.php';


$pdo = DBConnection::getConnection(); // Kết nối đến cơ sở dữ liệu
$productManager = new Product($pdo);
$userManager = new User($pdo);
$billManager = new Bill($pdo);
$mail = new sendmail();

if (isset($_SESSION['user_id'])) {
    $user = $userManager->getUserByUserId($_SESSION['user_id']);
    $defaultName = $user['name'];
    $defaultEmail = $user['email'];
    $defaultPhone = $user['phone'];
    $defaultAddress = $user['address'];
} else {
    // Người dùng chưa đăng nhập, đặt các giá trị mặc định là rỗng
    $defaultName = '';
    $defaultEmail = '';
    $defaultPhone = '';
    $defaultAddress = '';
}

// xóa sản phẩm và clearall
if (isset($_GET['action'])) {
    if ($_GET['action'] == "remove") {
        foreach ($_SESSION['cart'] as $key => $value) {
            if ($value['product_id'] == $_GET['product_id']) {
                unset($_SESSION['cart'][$key]);
            }
        }
    }
    if ($_GET['action'] == "clearall") {
        unset($_SESSION['cart']);
    }
}

if (isset($_POST['submit'])) {
    $totalAmount = $_POST['totalAmount'];
    $notes = $_POST['notes'];
    $email = '';
    // Kiểm tra xem người dùng có đăng nhập hay chưa
    if (isset($_SESSION['user_id'])) {
        // Đã đăng nhập
        $user_id = $_SESSION['user_id'];
        $user = $userManager->getUserByUserId($user_id);
        $email = $user['email'];
    } else {
        // Chưa đăng nhập, tạo user mới
        $email = $_POST['email'];
        $name = $_POST['name'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];

        // Tạo user mới với mật khẩu mặc định là 12345678
        $password = "12345678";
        $userManager->addUser($email, $password, $name, $address, $phone);
        $user_id = $pdo->lastInsertId();
    }

    // Tạo bill mới và lưu vào database
    $bill_id = $billManager->createBill($user_id, $totalAmount, $notes);
    // Lưu thông tin chi tiết đơn hàng (bill_detail)
    foreach ($_SESSION['cart'] as $item) {
        $product_id = $item['product_id'];
        $bill_quantity = $item['quantity'];
        $billManager->addBillDetail($bill_id, $product_id, $bill_quantity);
    }

    $title = "Đặt hàng tại Book Shop thành công";
    $bills_details = $billManager->getBillDetail($bill_id);
    $emailContent = getEmailContent($bills_details, $totalAmount);
    $content = $emailContent;
    header('Location:../partials/thanks.php');
    $mail->sentmail($title, $content, $email);

    // Xóa thông tin giỏ hàng sau khi tạo đơn hàng thành công
    unset($_SESSION['cart']);
}
    //Kiểm tra trung mail

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
    <?php
    require '../partials/navbar.php';
    ?>
    <!-- End Navbar -->
    <main>
        <div class="shop-form text-center m-5">
            <h2>ĐƠN HÀNG</h2>
        </div>
        <div class="container cart_top">
            <?php
            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            ?>
                <!-- Lấy giá trị totalAmount, display none để không hiển thị-->
                <div style="display: none;"><?php $totalAmount = $productManager->showCart($_SESSION['cart']); ?></div>
            <?php
                // function showCart($_SESSION['cart'])
                $showCart = $productManager->showCart($_SESSION['cart']);
            } else {
                echo "<div class='shop-form text-center m-5'>
            </div>";
            }
            ?>
        </div>
        <!-- info khách hàng -->
     <div class="shop-form text-center m-5">
            <h2>THÔNG TIN NHẬN HÀNG</h2>
        </div>
        <div class="container cart_bottom">
            <form method="post" action="cart.php" id="cart_form">
                <div class="form-row m-4" style="display: flex;">
                    <div class="form-group col-md-6 pe-2">
                        <input type="text" id="name" name="name" class="form-control" placeholder="Tên" value="<?php echo $defaultName; ?>">
                    </div>

                    <div class="form-group col-md-6 ps-2">
                        <input type="tel" id="phone" name="phone" class="form-control" placeholder="Số điện thoại" value="<?php echo $defaultPhone; ?>">
                    </div>
                </div>
                <div class="form-row m-4" style="display: flex;">
                    <div class="form-group col-md-6 pe-2">
                        <input type="text" id="address" name="address" class="form-control" placeholder="Địa chỉ" value="<?php echo $defaultAddress; ?>">
                    </div>

                    <div class="form-group col-md-6 ps-2">
                        <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="<?php echo $defaultEmail; ?>">
                    </div>
                </div>
                <div class="form-group m-4">
                    <textarea id="note" name="notes" class="form-control" placeholder="Ghi chú (nếu có)"></textarea>
                </div>
                <input type="hidden" name="totalAmount" value="<?php echo $totalAmount ?>">
                <div class="form-group m-4">
                    <button type="submit" id="muahang" name="submit" class="btn btn-primary" style="width: 100%;">Mua hàng</button>
                </div>
            </form>
        </div>
    </main>

    <!-- Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Thông báo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4>Bạn đã đặt hàng thành công, vui lòng chuyển tiền đến STK:091234567687(Ngân hàng Sacombank) để nhận được khóa học</h4>
                    <a href="../index.php"><button type="button" class="btn btn-primary">Tiếp tục mua hàng</button></a>
                    <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <?php
    require '../partials/footer.php';
    ?>
    <!-- End Footer -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="../assets/js/main.js"></script>
    <script>
        // $(document).ready(function() {
        //     $('#muahang').click(function() {
        //         $('#successModal').modal('show');
        //     });
        // });
    </script>
</body>
</html>
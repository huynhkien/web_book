<?php
session_start();
require_once '../src/DBConnection.php';
require_once '../src/Bill.php';
require_once '../src/Product.php';
$pdo = DBConnection::getConnection(); // Kết nối đến cơ sở dữ liệu
$bill_id = $_POST['bill_id'];
$billManager = new Bill($pdo);
$bills = $billManager->getAllBillByBillId($bill_id);
$bills_details = $billManager->getBillDetail($bill_id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/main.css">


    <title>Lịch sử mua hàng</title>
</head>

<body>
    <!-- Navbar -->
    <?php
    require '../partials/navbar.php';
    ?>
    <!-- End Navbar -->
    <main>
        <div class="container my-5">

            <div class="bottom-data">
                <div class="orders">
                    <div class="header text-center mb-5">
                        <h3>Thông tin chi tiết đơn hàng</h3>
                    </div>
                    <div class="row" style="background-color: #f8f8f8;">
                        <div class="col-lg-6">
                            <p><b>Khách hàng:</b> <?php echo $bills['name']; ?></p>
                            <p><b>Địa chỉ:</b> <?php echo $bills['address']; ?></p>
                        </div>
                        <div class="col-lg-6">
                            <p><b>SĐT:</b> <?php echo $bills['phone']; ?></p>
                            <p><b>Email:</b> <?php echo $bills['email']; ?></p>
                        </div>
                        <div class="col-lg-6">
                            <p><b>Tổng tiền:</b> <?php echo number_format($bills['total_amount'], 0, '.', '.'); ?>đ</p>
                            <div class="bill-detail-status">
                                <p>Trạng thái: </p>
                                <?php
                                $statusClass = '';
                                switch ($bills['status']) {
                                    case 'Đã giao hàng':
                                        $statusClass = 'completed';
                                        break;
                                    case 'Đã xác nhận':
                                        $statusClass = 'process';
                                        break;
                                    case 'Đã hủy đơn':
                                        $statusClass = 'cancelled';
                                        break;
                                    default:
                                        $statusClass = 'pending';
                                        break;
                                }
                                // echo '<td class="text-center"><span class="status ' . $statusClass . '">' . htmlspecialchars($bill['status']) . '</span></td>';
                                echo '<span class="status ' . $statusClass . '" data-bill_id="' . $bills['bill_id'] . '">' . htmlspecialchars($bills['status']) . '</span>';
                                ?>
                            </div>
                        </div>
                    </div>
                    <table id="example" class="table table-striped text-center" style="width:100%">
                        <thead>
                            <tr>
                                <th>Ảnh</th>
                                <th>Tên</th>
                                <th>Số lượng</th>
                                <th>Giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bills_details as $bill) : ?>
                                <tr>

                                    <td><img src="<?php echo $bill['img']; ?>" style="height: 100px;"></td>
                                    <td class="text-center"><?php echo $bill['productName']; ?></td>
                                    <td class="text-center"><?php echo $bill['bill_quantity']; ?></td>
                                    <td class="text-center"><span><?php echo number_format($bill['productPrice'] * $bill['bill_quantity'], 0, '.', '.'); ?>đ</span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <?php
    require '../partials/footer.php';
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.0.1/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="../assets/js/admin_dash_board.js"></script>
    <script src="../assets/js/main.js"></script>
</body>

</html>
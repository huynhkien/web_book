<?php
session_start();
require_once '../src/DBConnection.php';
require_once '../src/Bill.php';

$pdo = DBConnection::getConnection(); // Kết nối đến cơ sở dữ liệu

$user_id = $_GET['user_id'];
$billManager = new Bill($pdo);
$bills = $billManager->getAllBillByUserId($user_id);

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
                    <div class="header text-center">
                        <h3>Thông tin mua hàng</h3>
                    </div>
                    <table id="example" class="table table-striped text-center" style="width:100%">
                        <thead>
                            <tr>
                                <th>Khách hàng</th>
                                <th>Ngày lập</th>
                                <th>Giá</th>
                                <th>Trạng thái</th>
                                <th>Chi tiết</th>
                            </tr>
                        </thead>
                        </thead>
                        <tbody>
                            <?php foreach ($bills as $bill) : ?>
                                <tr>

                                    <td class="text-center"><?php echo $bill['name']; ?></td>
                                    <td class="text-center"><?php echo $bill['bill_date']; ?></td>
                                    <td class="text-center"><span><?php echo number_format($bill['total_amount'], 0, '.', '.'); ?>đ</span></td>
                                    <?php
                                    $statusClass = '';
                                    switch ($bill['status']) {
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
                                    echo '<td class="text-center"><span class="status ' . $statusClass . '" data-bill_id="' . $bill['bill_id'] . '">' . htmlspecialchars($bill['status']) . '</span></td>';
                                    ?>
                                    <td class="text-center" style="height: auto; text-align: center; line-height: auto;">
                                        <form method="post" action="user_bill_detail_history.php" style="margin-bottom: 5px;">
                                            <input type="hidden" name="bill_id" value="<?php echo $bill['bill_id']; ?>">
                                            <button type="submit" class="btn btn-xs btn-primary" name="detail">
                                                <i alt="Detail" class="bx bx-detail"></i>
                                            </button>
                                        </form>
                                    </td>
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
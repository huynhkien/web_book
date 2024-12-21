<?php
require_once '../src/DBConnection.php';
// require_once '../partials/check_admin.php';
require_once '../src/Bill.php';
$pdo = DBConnection::getConnection(); // Kết nối đến cơ sở dữ liệu
$billManager = new Bill($pdo);
$bills = $billManager->getAllBill();
?>
<!DOCTYPE html>
<html lang="en">

<?php
require '../partials/header_admin.php';
?>

<body>
    <!-- Sidebar -->
    <?php
    require '../partials/sidebar_admin.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Main Content -->
    <div class="content">
        <!-- Navbar -->
        <?php
        require '../partials/navbar_admin.php';
        ?>
        <!-- End of Navbar -->
        <main>
            <div class="header">
                <div class="left">
                    <h1>Đơn hàng</h1>
                </div>
            </div>
            <div class="bottom-data">
                <div class="orders">
                    <div class="header">
                        <i class='bx bx-receipt'></i>
                        <h3>Thông tin đơn hàng</h3>

                    </div>
                    <table id="example" class="table table-striped text-center" style="width:100%">
                        <thead>
                            <tr>
                                <th>Khách hàng</th>
                                <th>Ngày lập</th>
                                <th>Giá</th>
                                <th>Trạng thái</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        </thead>
                        <tbody>
                            <?php foreach ($bills as $bill) : ?>
                                <tr>

                                    <td class="text-center p-5"><?php echo $bill['name']; ?></td>
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
                                        <form method="post" action="show_bill_detail.php" style="margin-bottom: 5px;">
                                            <input type="hidden" name="bill_id" value="<?php echo $bill['bill_id']; ?>">
                                            <button type="submit" class="btn btn-xs btn-primary" name="detail">
                                                <i alt="Detail" class="bx bx-detail"></i>
                                            </button>
                                        </form>
                                        <form method="post" action="delete_bill.php">
                                            <input type="hidden" name="bill_id" value="<?php echo $bill['bill_id']; ?>">
                                            <button type="submit" class="btn btn-xs btn-danger" name="delete">
                                                <i alt="Delete" class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Chọn trạng thái</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="./update_status.php" method="POST">
                        <input type="hidden" name="bill_id" id="bill_id">
                        <div class="mb-3">
                            <label for="statusSelect" class="form-label">Trạng thái</label>
                            <select class="form-select" name="status" id="statusSelect">
                                <option value="pending">Chưa xác nhận</option>
                                <option value="confirmed">Đã xác nhận</option>
                                <option value="delivered">Đã giao hàng</option>
                                <option value="cancelled">Đã hủy đơn</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <script src="https://cdn.datatables.net/2.0.1/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="../assets/js/admin_dash_board.js"></script>

    <script>
        document.title = "Đơn hàng";;
    </script>
</body>

</html>
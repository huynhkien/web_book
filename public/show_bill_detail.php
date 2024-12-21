<?php
require_once '../src/DBConnection.php';
// require_once '../partials/check_admin.php';
require_once '../src/Bill.php';
require_once '../src/Product.php';


$bill_id = $_POST['bill_id'];

$pdo = DBConnection::getConnection(); // Kết nối đến cơ sở dữ liệu
$billManager = new Bill($pdo);
$bills = $billManager->getAllBillByBillId($bill_id);
$bills_details = $billManager->getBillDetail($bill_id);
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
                        <h3>Thông tin chi tiết đơn hàng</h3>

                    </div>
                    <div class="row">
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

            <!-- modal -->
            <!-- Modal -->
            <div class=" modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
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
        </main>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <script src="https://cdn.datatables.net/2.0.1/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="../assets/js/admin_dash_board.js"></script>

    <script>
        document.title = "Đơn hàng";
        //     $(document).ready(function() {
        //         $('#example').DataTable();
        //     });
    </script>
</body>

</html>
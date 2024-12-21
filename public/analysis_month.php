<?php
require_once '../src/DBConnection.php';
require_once '../src/Product.php';
require_once '../src/User.php';
require_once '../src/Bill.php';
$pdo = DBConnection::getConnection(); // Kết nối đến cơ sở dữ liệu
$productManager = new Product($pdo);
$userManager = new User($pdo);
$billManager = new Bill($pdo);

// Truy vấn cơ sở dữ liệu để lấy dữ liệu Ngày từ bảng bill
$query = $pdo->query("SELECT MONTH(bill_date) AS thang, SUM(total_amount) AS tong_doanh_thu FROM bill GROUP BY MONTH(bill_date)");
$results = $query->fetchAll(PDO::FETCH_ASSOC);

// Khởi tạo mảng labels và data
$labels = [];
$data = [];

// Duyệt qua kết quả từ truy vấn để điền dữ liệu vào mảng labels và data
foreach ($results as $row) {
    $labels[] = $row['thang'];
    $data[] = $row['tong_doanh_thu'];
}

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
                    <h1>Dashboard</h1>
                    <!-- <ul class="breadcrumb">
                        <li><a href="#">
                                Analytics
                            </a></li>
                        /
                        <li><a href="#" class="active">Shop</a></li>
                    </ul> -->
                </div>
                <!-- <a href="#" class="report">
                    <i class='bx bx-cloud-download'></i>
                    <span>Download CSV</span>
                </a> -->
            </div>

            <!-- Insights -->
            <ul class="insights">
                <a href="./show_bill.php">
                    <li>
                        <i class='bx bx-receipt' style="background: var(--light-primary);
                                                            color: var(--primary);">
                        </i>
                        <span class="info">
                            <h3>
                                <?php
                                $billTotal = $billManager->getTotalNumberOfOrders();
                                echo $billTotal;
                                ?>
                            </h3>
                            <p>Paid Order</p>
                        </span>
                    </li>
                </a>
                <a href="./show_user.php">
                    <li>
                        <i class='bx bx-group' style="background: var(--light-warning);
                                                        color: var(--warning);">
                        </i>
                        <span class="info">
                            <h3>
                                <?php
                                $userTotal = $userManager->getTotalNumberOfUsers();
                                echo $userTotal;
                                ?>
                            </h3>
                            <p>Khách hàng</p>
                        </span>
                    </li>
                </a>
                <a href="./show_product.php">
                    <li>
                        <i class='bx bx-package' style="background: var(--light-success);
                                                        color: var(--success);">
                        </i>
                        <span class="info">
                            <h3>
                                <?php
                                $productTotal = $productManager->getTotalNumberOfProduct();
                                echo $productTotal;
                                ?>
                            </h3>
                            <p>Sản phẩm</p>
                        </span>
                    </li>
                </a>
                <li>
                    <i class='bx bx-dollar-circle' style="background: var(--light-danger);
                                                        color: var(--danger);">
                    </i>
                    <span class="info">
                        <h3>
                            <?php
                            $totalAmount = $billManager->getTotalAmountOfAllBills();
                            echo number_format($totalAmount, 0, '.', '.');
                            ?>
                        </h3>
                        <p>Doanh thu</p>
                    </span>
                </li>
            </ul>
            <!-- End of Insights -->

            <div class="bottom-data">
                <div class="orders">
                    <div class="header">
                        <i class='bx bx-receipt'></i>
                        <h3>Thống kê</h3>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="statDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Thống kê
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="statDropdown">
                            <li><a class="dropdown-item" href="./analysis.php" id="daily">Theo ngày</a></li>
                            <li><a class="dropdown-item" href="./analysis_month.php" id="monthly">Theo tháng</a></li>
                        </ul>
                    </div>
                    <div class="analysis">
                        <div class="container mt-5">
                            <!-- analysis daily -->
                            <div class="row" id="daily">
                                <div class="col">
                                    <h2 class="text-center mb-4">Thống kê doanh thu theo tháng</h2>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">tháng</th>
                                                <th scope="col">Doanh thu (VNĐ)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($results as $row) : ?>
                                                <tr>
                                                    <td>Tháng <?php echo $row['thang']; ?></td>
                                                    <td><?php echo number_format($row['tong_doanh_thu'], 0, ',', '.'); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <canvas id="revenueChart" width="800" height="400"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>

    <script src="https://cdn.datatables.net/2.0.1/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="../assets/js/admin_dash_board.js"></script>

    <script>
        // Dữ liệu cho biểu đồ
        var chartData = {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: <?php echo json_encode($data); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.9)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };
        // Cấu hình biểu đồ
        var chartOptions = {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };
        // Vẽ biểu đồ
        var revenueChart = new Chart(document.getElementById('revenueChart').getContext('2d'), {
            type: 'bar',
            data: chartData,
            options: chartOptions
        });
    </script>
</body>

</html>
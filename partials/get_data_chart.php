<?php
require_once '../src/DBConnection.php';
$pdo = DBConnection::getConnection(); // Kết nối đến cơ sở dữ liệu

// Xử lý yêu cầu và trả về dữ liệu thống kê tương ứng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $statType = $_POST['statType'];
    // Xử lý yêu cầu và trả về dữ liệu thống kê tương ứng (tùy thuộc vào 'statType')
    // Ví dụ:
    if ($statType === 'daily') {
        // Lấy dữ liệu thống kê theo ngày
        $data = getDataForDailyStat();
        echo json_encode($data);
    } elseif ($statType === 'monthly') {
        // Lấy dữ liệu thống kê theo tháng
        $data = getDataForMonthlyStat();
        echo json_encode($data);
    }
}

// Hàm lấy dữ liệu thống kê theo ngày
function getDataForDailyStat()
{
    $pdo = DBConnection::getConnection(); // Kết nối đến cơ sở dữ liệu
    // Truy vấn cơ sở dữ liệu để lấy dữ liệu cần thiết từ bảng bill
    $query = $pdo->query("SELECT DATE(bill_date) AS ngay, SUM(total_amount) AS tong_doanh_thu FROM bill GROUP BY DATE(bill_date)");
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($results as $row) {
        $labels[] = $row['ngay'];
        $data[] = $row['tong_doanh_thu'];
    }
}

// Hàm lấy dữ liệu thống kê theo tháng
function getDataForMonthlyStat()
{
    $pdo = DBConnection::getConnection(); // Kết nối đến cơ sở dữ liệu
    // Truy vấn cơ sở dữ liệu để lấy dữ liệu cần thiết từ bảng bill
    $query = $pdo->query("SELECT MONTH (bill_date) AS thang, SUM(total_amount) AS tong_doanh_thu FROM bill GROUP BY MONTH(bill_date)");
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($results as $row) {
        $labels[] = $row['thang'];
        $data[] = $row['tong_doanh_thu'];
    }
}

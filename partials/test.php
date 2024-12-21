<?php
session_start();
require_once '../src/DBConnection.php';
$pdo = DBConnection::getConnection(); // Kết nối đến cơ sở dữ liệu

// Kiểm tra xem người dùng đã đăng nhập chưa
if (isset($_SESSION['user_id'])) {
    // Người dùng đã đăng nhập
    $user_id = $_SESSION['user_id'];
    $name = $_SESSION['name'];
    $role_id = $_SESSION['role_id'];

    // Đây là nơi bạn có thể thực hiện các hành động phù hợp cho người dùng đã đăng nhập, ví dụ: hiển thị nút logout, truy cập các chức năng dành cho người dùng đã đăng nhập, vv.
    echo "Xin chào $name! Bạn đã đăng nhập với vai trò có mã $role_id.";
    echo '<br><a href="logout.php">Đăng xuất</a>';
} else {
    // Người dùng chưa đăng nhập, bạn có thể chuyển hướng hoặc hiển thị một thông báo
    echo "Bạn chưa đăng nhập.";
    echo '<br><a href="login.php">Đăng nhập</a>';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê doanh thu</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <div class="row">
            <div class="col">
                <h2 class="text-center mb-4">Thống kê doanh thu theo ngày</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Ngày</th>
                            <th scope="col">Doanh thu (VNĐ)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $row) : ?>
                            <tr>
                                <td>Ngày <?php echo $row['ngay']; ?></td>
                                <td><?php echo number_format($row['tong_doanh_thu'], 0, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <canvas id="revenueChart" width="800" height="400"></canvas>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS và Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
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

<!-- mail -->
<html>

<head>
    <title>Test Email</title>
    <style>
        /* CSS styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #007bff;
            color: #fff;
            padding: 10px 0;
            text-align: center;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }

        .content {
            padding: 0;
        }

        .footer {
            background-color: #007bff;
            color: #fff;
            padding: 10px 0;
            text-align: center;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Thông tin đơn hàng</h1>
        </div>
        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th style="text-align: left;">Tên Sản Phẩm</th>
                        <th style="text-align: center;">Số Lượng</th>
                        <th style="text-align: center;">Giá Bán</th>
                        <th style="text-align: center;">Thành Tiền</th>
                    </tr>
                </thead>
                <!-- Phần nội dung -->
                <tr>
                    <td colspan="3" style="text-align: right;">Tổng Tiền Đơn Hàng:</td>
                    <td style="text-align: right;">' . number_format($total_amount, 0, '.', '.') . 'đ</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="footer">
            <h3>Cảm ơn đã mua sắm</h3>
        </div>
    </div>
</body>

</html>




<?php
// session_start();

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

class sentmail
{
    public function dathangmail($tieude, $noidung, $user_mail)
    {

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'shopjpfigure@gmail.com';                     //SMTP username
            $mail->Password   = 'jongefqcwtquhztn';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('shopjpfigure@gmail.com', 'Figure shop');
            $mail->addAddress($user_mail);     //Add a recipient
            $mail->addCC('shopjpfigure@gmail.com');
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $tieude;
            $mail->Body    = $noidung;
            // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}




$noidung = '<html>
                    <head>
                        <title>Thông tin đơn hàng</title>
                        <style>
                            /* CSS styles */
                            body {
                                font-family: Arial, sans-serif;
                                background-color: #f0f0f0;
                            }
                            .container {
                                max-width: 600px;
                                margin: 0 auto;
                                padding: 20px;
                                background-color: #fff;
                                border-radius: 5px;
                                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                            }
                            .header {
                                background-color: #007bff;
                                color: #fff;
                                padding: 10px 0;
                                text-align: center;
                                border-top-left-radius: 5px;
                                border-top-right-radius: 5px;
                            }
                            .content {
                                padding:0;
                            }
                            .footer {
                                background-color: #007bff;
                                color: #fff;
                                padding: 10px 0;
                                text-align: center;
                                border-bottom-left-radius: 5px;
                                border-bottom-right-radius: 5px;
                            }
                            table {
                                border-collapse: collapse;
                                width: 100%;
                            }
                            th, td {
                                border: 1px solid #dddddd;
                                text-align: left;
                                padding: 8px;
                            }
                            th {
                                background-color: #f2f2f2;
                            }
                            tr:nth-child(even) {
                                background-color: #f9f9f9;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <div class="header">
                                <h1>Thông tin đơn hàng</h1>
                            </div>
                            <div class="content">
                                <table>
                                    <thead>
                                        <tr>
                                            <th style="text-align: left;">Tên Sản Phẩm</th>
                                            <th style="text-align: center;">Số Lượng</th>
                                            <th style="text-align: center;">Giá Bán</th>
                                            <th style="text-align: center;">Thành Tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

$bills_details = $billManager->getBillDetail($bill_id);
foreach ($bills_details as $bill_detail) {
    // Thêm một hàng mới vào biến $tableBody cho mỗi mục chi tiết đơn hàng
    $noidung .= '<tr>
                        <td>' . $bill_detail['productName'] . '</td>
                        <td style="text-align: center;">' . $bill_detail['bill_quantity'] . '</td>
                        <td style="text-align: center;">' . $bill_detail['productPrice'] . '</td>
                        <td style="text-align: right;">' . number_format($bill_detail['productPrice'] * $bill_detail['bill_quantity'], 0, '.', '.') . 'đ</td>
                    </tr>';
}
$noidung .= '<tr>
                        <td colspan="3" style="text-align: right;">Tổng Tiền Đơn Hàng:</td>
                        <td style="text-align: right;">' . number_format($totalAmount, 0, '.', '.') . 'đ</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="footer">
            <h3>Cảm ơn đã mua sắm</h3>
        </div>
    </div>
</body>
</html>';

<!-- email_content.php -->
<?php
// Hàm này trả về phần nội dung HTML cho email
function getEmailContent($bills_details, $totalAmount)
{
    $emailContent = '
        <html>
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

    // Thêm phần nội dung cho từng sản phẩm trong đơn hàng
    foreach ($bills_details as $bill_detail) {
        $emailContent .= '<tr>
                            <td>' . $bill_detail['productName'] . '</td>
                            <td style="text-align: center;">' . $bill_detail['bill_quantity'] . '</td>
                            <td style="text-align: center;">' . $bill_detail['productPrice'] . '</td>
                            <td style="text-align: right;">' . number_format($bill_detail['productPrice'] * $bill_detail['bill_quantity'], 0, '.', '.') . 'đ</td>
                        </tr>';
    }

    // Thêm tổng tiền của đơn hàng
    $emailContent .= '<tr>
                        <td colspan="3" style="text-align: right;">Tổng Tiền Đơn Hàng:</td>
                        <td style="text-align: right;">' . number_format($totalAmount, 0, '.', '.') . 'đ</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="footer">
            <h3>Vui lòng chuyển tiền đến STK:vui lòng chuyển tiền đến STK:091234567687(Ngân hàng Sacombank) để nhận được khóa học</h3>

        </div>
    </div>
</body>
</html>';

    return $emailContent;
}
?>
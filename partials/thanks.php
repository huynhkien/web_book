<?php
session_start();
require_once '../src/DBConnection.php';

$pdo = DBConnection::getConnection(); // Kết nối đến cơ sở dữ liệu
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/main.css">

    <title>Cảm ơn đã mua sắm</title>
</head>

<body>
    <!-- Navbar -->
    <?php
    require '../partials/navbar.php';
    ?>
    <!-- End Navbar -->
    <main>
        <div class="row thanks-content">
            <div class="col-lg-6 content text-center">
                <div class="content-text">
                    <h2>Cảm ơn bạn đã mua sắm</h2>
                    <p>Sẽ có nhân viên sớm liên hệ với bạn</p>
                    <p>Hãy kiểm tra mail để xem chi tiết đơn hàng</p>
                    <a href="../index.php"><button class="btn btn-xs btn-primary">Tiếp tục mua hàng</button></a>
                </div>
            </div>
            <div class="col-lg-6 img">
                <img src="../assets/img/slide/lienhe.png" alt="">
            </div>
        </div>

    </main>
    <?php
    require '../partials/footer.php';
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="../assets/js/main.js"></script>
</body>

</html>
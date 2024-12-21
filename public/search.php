<?php
session_start();
require_once '../src/DBConnection.php';
$pdo = DBConnection::getConnection(); // Kết nối đến cơ sở dữ liệu


if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];

    // Truy vấn SQL để lấy ra sản phẩm dựa trên từ khoá
    $stmt = $pdo->prepare('SELECT * FROM product WHERE productName LIKE :keyword OR author LIKE :keywordauthor');
    $stmt->execute([
        ':keyword' => '%' . $keyword . '%',
        ':keywordauthor' => $keyword . '%'
    ]);

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
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
        <div class="container">
            <!-- Show product -->
            <div class="text-center">
                <div class="row search-header">
                    <div class="col-lg-6">
                        <img src="../assets/img/slide/Bino_Fahasa_840x320_1.jpg" alt="" style="width: 100%; object-fit: cover;">
                    </div>
                    <div class="col-lg-6 text-search">
                        <h1 class='text-search-result'>Kết quả tìm kiếm: <?php echo $keyword; ?></h1>
                    </div>
                </div>

                <div id="gallery" class="row">
                    <?php foreach ($products as $product) { ?>
                        <div class="col-lg-2 col-md-3 col-sm-6 card product">
                            <a href="product.php?product_id=<?php echo $product['product_id']; ?>">
                                <div class="card-img">
                                    <img src="../assets/img/upload/<?php echo basename($product['img']); ?>" alt="">
                                </div>
                                <div class="card-info">
                                    <p class="text-title productTitle"><?php echo $product['productName']; ?></p>
                                </div>
                            </a>
                            <div class="card-footer">
                                <span class="text-title"><?php echo number_format($product['productPrice'], 0, '.', '.'); ?>đ</span>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <!-- End product -->
        </div>
    </main>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="../assets/js/main.js"></script>
</body>

</html>
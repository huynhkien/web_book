<?php
require_once '../src/DBConnection.php';
require_once '../src/Product.php';

$pdo = DBConnection::getConnection(); // Kết nối đến cơ sở dữ liệu


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $productManager = new Product($pdo);
    // Lấy dữ liệu từ form
    $productName = $_POST["productName"];
    $catalog_id = $_POST["catalog_id"];
    $author = $_POST["author_id"];
    $productPrice = $_POST["productPrice"];
    $description = $_POST["description"];
    $img = $_FILES['img']['tmp_name'];
    $quantity = $_POST["quantity"];

    // Xử lý và di chuyển ảnh chính vào thư mục upload
    $uploadDirMain = '../assets/img/upload/';
    $uploadFileMain = $uploadDirMain . basename($_FILES['img']['name']);
    move_uploaded_file($_FILES['img']['tmp_name'], $uploadFileMain);
    $img = $uploadFileMain;


    // Thêm sản phẩm
    $productManager->addProduct($productName, $catalog_id, $author, $productPrice, $description, $img, $quantity);

    // Lấy product_id của sản phẩm vừa được thêm vào
    $product_id = $pdo->lastInsertId();
    // Thêm ảnh phụ vào bảng imglist (nếu có)
    if (!empty($_FILES['additionalImages']['tmp_name'])) {
        $additionalImagePaths = [];
        foreach ($_FILES['additionalImages']['tmp_name'] as $index => $tmp_name) {
            // Xử lý và di chuyển ảnh phụ vào thư mục upload
            $uploadDirAdditional = '../assets/img/upload/';
            $uploadFileAdditional = $uploadDirAdditional . basename($_FILES['additionalImages']['name'][$index]);
            move_uploaded_file($tmp_name, $uploadFileAdditional);
            // Lưu đường dẫn ảnh phụ vào mảng
            $additionalImagePaths[] = $uploadFileAdditional;
        }
        // Gọi phương thức để thêm ảnh phụ vào bảng imglist
        $productManager->addProductImages($product_id, $additionalImagePaths);
    }

    
    $_POST = array();
}

$catalogs = $pdo->query("SELECT * FROM catalog")->fetchAll(PDO::FETCH_ASSOC);
$authors = $pdo->query("SELECT * FROM author")->fetchAll(PDO::FETCH_ASSOC);


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
                    <h1>Sản phẩm</h1>
                </div>
            </div>
            <div class="bottom-data">
                <div class="orders">
                    <div class="header">
                        <i class='bx bx-receipt'></i>
                        <h3>Thêm sản phẩm</h3>
                    </div>

                    <!-- Them san pham -->
                    <div class="container">
                        <form method="post" action="" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="catalog_id">Danh mục :</label>
                                <select class="form-control" id="catalog_id" name="catalog_id">
                                    <?php foreach ($catalogs as $catalog) : ?>
                                        <option value="<?= $catalog['catalog_id'] ?>"><?= $catalog['catalogName'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="productName">Tên :</label>
                                <input type="text" class="form-control" id="productName" name="productName" placeholder="Nhập tên" required>
                            </div>
                            <div class="form-group">
                                <label for="productPrice">Giá :</label>
                                <input type="number" class="form-control" id="productPrice" name="productPrice" placeholder="Nhập giá" required>
                            </div>

                            <div class="form-group">
                                <label for="img">Chọn ảnh từ file:</label>
                                <input type="file" class="form-control" id="img" name="img">
                            </div>

                            <div class="form-group">
                                <label for="additionalImages">Ảnh phụ:</label>
                                <input type="file" class="form-control" id="additionalImages" name="additionalImages[]" multiple>
                                <small id="additionalImagesHelp" class="form-text text-muted">Bạn có thể chọn nhiều ảnh bằng cách giữ phím Ctrl hoặc Shift khi chọn.</small>
                            </div>

                            <div class="form-group">
                                <label for="author_id">Tác giả :</label>
                                <select class="form-control" id="author_id" name="author_id">
                                    <?php foreach ($authors as $authors) : ?>
                                        <option value="<?= $authors['author_id'] ?>"><?= $authors['authorName'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="content">Số lượng:</label>
                                <textarea class="form-control" id="quantity" name="quantity" placeholder="Nhập số lượng" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="content">Mô tả:</label>
                                <textarea class="form-control" id="description" name="description" placeholder="Nhập mô tả" required></textarea>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">Thêm</button>
                        </form>
                    </div>
                    <!-- End them san pham -->
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
        document.title = "Sản phẩm";;
    </script>
</body>

</html>
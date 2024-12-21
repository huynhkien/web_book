<?php
require_once '../src/DBConnection.php';
require_once '../src/Product.php';

$product_id = $_POST['product_id'];

$pdo = DBConnection::getConnection(); // Kết nối đến cơ sở dữ liệu
$productManager = new Product($pdo);
$products = $productManager->getProductByProductId($product_id);
$imglist  = $productManager->getProductImages($product_id);

if (isset($_POST['submit'])) {
    $productManager->updateProductInfo($product_id, $_POST, $_FILES['img'], $_FILES['additionalImages']);

    header('Location: ./show_product.php');
    exit;
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
                        <h3>Chỉnh sửa sản phẩm</h3>
                    </div>
                    <div class="container">

                        <form method="post" action="" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="catalog_id">Danh mục:</label>
                                <select class="form-control" id="catalog_id" name="catalog_id">
                                    <?php foreach ($catalogs as $catalog) : ?>
                                        <option value="<?= $catalog['catalog_id'] ?>"><?= $catalog['catalogName'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group" style="display:none;">
                                <label for="productName">product_id</label>
                                <input type="text" class="form-control" id="product_id" name="product_id" value="<?= htmlspecialchars($products['product_id']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="productName">Tên :</label>
                                <input type="text" class="form-control" id="productName" name="productName" value="<?= htmlspecialchars($products['productName']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="price">Giá:</label>
                                <input type="number" class="form-control" id="productPrice" name="productPrice" value="<?= htmlspecialchars($products['productPrice']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="img">Chọn ảnh từ file:</label>
                                <input type="file" class="form-control" id="img" name="img">
                            </div>
                            <div class="form-group">
                                <label for="additionalImages">Ảnh phụ:</label>
                                <input type="file" class="form-control" id="additionalImages" name="additionalImages[]" multiple>
                                <small id="additionalImagesHelp" class="form-text text-muted">Bạn có thể chọn nhiều ảnh</small>
                            </div>
                            <div class="form-group">
                                <label for="author">Tác giả:</label>
                                <select class="form-control" id="author" name="author">
                                    <?php foreach ($authors as $author) : ?>
                                        <option value="<?= $author['author_id'] ?>"><?= $author['authorName'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="content">Số lượng:</label>
                                <input type="text" class="form-control" id="quantity" name="quantity" value="<?= htmlspecialchars($products['quantity']) ?>"></input>
                            </div>
                            <div class="form-group">
                                <label for="content">Mô tả:</label>
                                <textarea class="form-control" id="description" name="description" placeholder="Nhập mô tả" required><?= htmlspecialchars($products['description']) ?></textarea>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary mt-2">Sửa thông tin</button>
                        </form>
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
        document.title = "Sản phẩm";;
    </script>
</body>

</html>
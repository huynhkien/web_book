<?php
require_once '../src/DBConnection.php';
require_once '../src/Product.php';
require_once '../src/Author.php';
require_once '../src/Category.php';

$pdo = DBConnection::getConnection(); // Kết nối đến cơ sở dữ liệu
$productManager = new Product($pdo);
$products = $productManager->getAllProducts();

// Lấy id 
$categoryManager = new Category($pdo);
$authorManager = new Author($pdo);
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
                        <h3>Thông tin sản phẩm</h3>

                    </div>
                    <a href="./add_product.php" class="btn btn-primary" style="margin-bottom: 30px;">
                        <i class="fa fa-plus"></i> Thêm sản phẩm
                    </a>
                    <table id="example" class="table table-striped text-center" style="width:100%">
                        <thead>
                            <tr>
                                <th>Ảnh</th>
                                <th>Tên</th>
                                <th>Danh mục</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Tác giả</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product) : ?>
                                <tr>
                                    <td class='p-5'><img src="<?php echo $product['img']; ?>" style="height: 100px;"></td>
                                    <td class="text-center"><?php echo $product['productName']; ?></td>
                                    <td class="text-center"><?php 
                                        $category = $categoryManager->getCategory($product['catalog_id']);
                                        echo $category['catalogName']; 
                                        ?></td>
                                    <td class="text-center"><span><?php echo number_format($product['productPrice'], 0, '.', '.'); ?>đ</span></td>
                                    <td class="text-center"><?php echo $product['quantity']; ?></td>
                                    <td class="text-center"><?php 
                                        $author = $authorManager->getAuthor($product['author']);
                                        echo $author['authorName']; 
                                    ?></td>
                                    <td class="text-center" style="height: auto; text-align: center; line-height: auto;">
                                        <form method="post" action="edit_product.php" style="margin-bottom: 5px;">
                                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                            <button type="submit" class="btn btn-xs btn-primary" name="detail">
                                                <i alt="Edit" class="bx bx-pencil"></i>
                                            </button>
                                        </form>
                                        <form method="post" action="delete_product.php">
                                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                            <button type="submit" class="btn btn-xs btn-danger" name="delete">
                                                <i alt="Delete" class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                        <form method="post" action="show_comment.php">
                                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                            <button type="submit" class="btn btn-xs btn-primary mt-1" name="comment">
                                               <i class='bx bxs-message-dots'></i>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <script src="https://cdn.datatables.net/2.0.1/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="../assets/js/admin_dash_board.js"></script>

    <script>
        document.title = "Sản phẩm";
        //     $(document).ready(function() {
        //         $('#example').DataTable();
        //     });
    </script>
</body>

</html>
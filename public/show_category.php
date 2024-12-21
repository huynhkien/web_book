<?php
require_once '../src/DBConnection.php';
require_once '../src/Category.php';

$pdo = DBConnection::getConnection(); // Kết nối đến cơ sở dữ liệu
$categoryManager = new Category($pdo);
$categories = $categoryManager->getAllCategory();

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
                    <h1>Danh Mục</h1>
                </div>
            </div>
            <div class="bottom-data">
                <div class="orders">
                    <div class="header">
                        <i class='bx bx-receipt'></i>
                        <h3>Thông tin danh mục</h3>

                    </div>
                    <a href="./add_category.php" class="btn btn-primary" style="margin-bottom: 30px;">
                        <i class="fa fa-plus"></i> Thêm danh mục
                    </a>
                    <table id="example" class="table table-striped text-center" style="width:100%">
                        <thead>
                            <tr>
                                <th>Image</th>
                                 <th>Category</th>
                                 <th>Action</th>
                            </tr>
                        </thead>
                        <tbody >
                            <?php foreach ($categories as $categories) : ?>
                                <tr >
                                <td class='p-5'><img src="<?php echo $categories['img']; ?>" style="height: 80px;"></td>
                                    <td class="text-center"><?php echo $categories['catalogName']; ?></td>
                                     
                                    <td class="">
                                       <form method="post" action="edit_category.php" class="mb-1">
                                            <input type="hidden" name="catalog_id" value="<?php echo $categories['catalog_id']; ?>">
                                            <button type="submit" class="btn btn-xs btn-primary" name="detail">
                                                <i alt="Edit" class="bx bx-pencil"></i>
                                            </button>
                                           
                                        </form>
                                        <form method="post" action="delete_category.php">
                                            <input type="hidden" name="catalog_id" value="<?php echo $categories['catalog_id']; ?>">
                                            <button type="submit" class="btn btn-xs btn-danger" name="delete">
                                                <i alt="Delete" class="bx bx-trash"></i>
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
        document.title = "Danh Mục";
        //     $(document).ready(function() {
        //         $('#example').DataTable();
        //     });
    </script>
</body>

</html>
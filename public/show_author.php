<?php
require_once '../src/DBConnection.php';
require_once '../src/Author.php';

$pdo = DBConnection::getConnection(); // Kết nối đến cơ sở dữ liệu
$authorManager = new Author($pdo);
$authors = $authorManager->getAllAuthor();

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
                    <h1>Tác giả</h1>
                </div>
            </div>
            <div class="bottom-data">
                <div class="orders">
                    <div class="header">
                        <i class='bx bx-receipt'></i>
                        <h3>Thông tin tác giả</h3>

                    </div>
                    <a href="./add_author.php" class="btn btn-primary" style="margin-bottom: 30px;">
                        <i class="fa fa-plus"></i> Thêm tác giả
                    </a>
                    <table id="example" class="table table-striped text-center" style="width:100%">
                        <thead>
                            <tr>
                                <th>Id</th>
                                 <th>Author</th>
                                 <th>Action</th>
                            </tr>
                        </thead>
                        <tbody >
                            <?php foreach ($authors as $authors) : ?>
                                <tr >
                                    <td class="text-center p-5"><?php echo $authors['author_id']; ?></td>
                                    <td class="text-center"><?php echo $authors['authorName']; ?></td>
                                     
                                    <td class="">
                                       <form method="post" action="edit_author.php" class="mb-1">
                                            <input type="hidden" name="author_id" value="<?php echo $authors['author_id']; ?>">
                                            <button type="submit" class="btn btn-xs btn-primary" name="detail">
                                                <i alt="Edit" class="bx bx-pencil"></i>
                                            </button>
                                           
                                        </form>
                                        <form method="post" action="delete_author.php">
                                            <input type="hidden" name="author_id" value="<?php echo $authors['author_id']; ?>">
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
        document.title = "Tác Giả";
        //     $(document).ready(function() {
        //         $('#example').DataTable();
        //     });
    </script>
</body>

</html>
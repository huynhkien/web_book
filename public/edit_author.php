<?php
require_once '../src/DBConnection.php';
require_once '../src/Author.php';

    $author_id = $_POST['author_id'];
    $pdo = DBConnection::getConnection(); 
    $authorManager = new Author($pdo);
    $author = $authorManager->getAuthor($author_id);
    
    if (isset($_POST['submit'])) {
        $authorManager->updateAuthor($author_id, $_POST['authorName']);
        header('Location: ./show_author.php');
        exit;
    }
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
                        <h3>Cập nhật thông tin tác giả</h3>
                    </div>

                    <!-- Them san pham -->
                    <form method="post" action="">
                        <input type="hidden" name="author_id" value="<?= $author_id ?>">
                        <div class="form-group">
                            <label for="authorName">Tên tác giả:</label>
                            <input type="text" class="form-control" id="authorName" name="authorName" placeholder="Nhập tên danh mục" value="<?= htmlspecialchars($author['authorName']) ?>" required >
                        </div>

                        <button type="submit" name="submit" class="btn btn-primary mt-2">Cập nhật</button>
                    </form>

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
        document.title = "Danh mục";;
    </script>
</body>

</html>
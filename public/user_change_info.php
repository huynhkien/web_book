<?php
session_start();
require_once '../src/DBConnection.php';
require_once '../src/User.php';

$pdo = DBConnection::getConnection(); // Kết nối đến cơ sở dữ liệu

$user_id = $_GET['user_id'];

$userManager = new User($pdo);
$users = $userManager->getUserByUserId($user_id);

if (isset($_POST['submit'])) {
    $userManager->updateUser($user_id, $_POST);
    header('Location: ../index.php');
    exit;
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

    <title>Thay đổi thông tin</title>
</head>

<body>
    <!-- Navbar -->
    <?php
    require '../partials/navbar.php';
    ?>
    <!-- End Navbar -->
    <main>
        <div class="container my-5">

            <div class="bottom-data">
                <div class="orders">
                    <div class="header text-center">
                        <h3>Chỉnh sửa thông tin</h3>
                    </div>
                    <div class="container">
                        <form method="post" action="">
                            <div class="form-group" style="display:none;">
                                <label for="user_id">user_id</label>
                                <input type="text" class="form-control" id="user_id" name="user_id" value="<?= htmlspecialchars($users['user_id']) ?>">
                            </div>
                            <div class="form-group my-2">
                                <label for="name">Tên khách hàng</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($users['name']) ?>">
                            </div>
                            <div class="form-group my-2">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($users['email']) ?>">
                            </div>
                            <div class="form-group my-2">
                                <label for="address">Địa chỉ</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($users['address']) ?>">
                            </div>
                            <div class="form-group my-2">
                                <label for="phone">Số điện thoại</label>
                                <input type="tel" class="form-control" pattern="[0-9]{10}" id="phone" name="phone" value="<?= htmlspecialchars($users['phone']) ?>">
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary mt-2">Sửa thông tin</button>
                        </form>
                    </div>
                </div>
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
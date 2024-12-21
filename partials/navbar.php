<?php
// Tính tổng số lượng sản phẩm trong giỏ hàng
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $totalItems = 0; // Nếu không có sản phẩm trong giỏ hàng, số lượng là 0
} else {
    $totalItems = count($_SESSION['cart']); // Nếu có sản phẩm trong giỏ hàng, tính số lượng
}

?>
    <section>
    <div>
        <img class="w-100" src='../assets/img/header/Week2_T424_Banner_Header_1263x60.jpg'/>
    </div>
    <nav class="navbar navbar-expand-sm navbar-dark bg-light  " style="display: block;">
    
        <div class="container-fluid sidebar">
            <a class="navbar-brand footer-logo" href="../index.php">
                <img src="https://cdn0.fahasa.com/skin/frontend/ma_vanese/fahasa/images/fahasa-logo.png" class="img-fluid" alt="logo" >
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mynavbar">
                <!-- Search Bar -->
                <form class="d-flex mx-auto custom-search-form" method="get" action="../public/search.php">
                    <input class="form-control me-2 " type="text" placeholder="Search" aria-label="Search" id="live_search" autocomplete="off" name="keyword">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                    <!--Kết quả tìm kiếm-->
                    <div id="searchresult"></div>
                </form>

                <ul class="navbar-nav logo mx=0">
                    <?php
                    if (isset($_SESSION['user_id'])) { // Kiểm tra session
                    ?> <li class="nav-item dropdown test mx-2">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-user text-dark"></i>
                            </a>
                            <ul class="dropdown-menu text-dark">
                                <li><a class="dropdown-item d-flex user-setting" href="../public/user_change_info.php?user_id=<?php echo $_SESSION['user_id']; ?>">
                                        Thay đổi thông tin</a></li>
                                <li><a class="dropdown-item d-flex user-setting" href="../public/user_bill_history.php?user_id=<?php echo $_SESSION['user_id']; ?>">
                                        Lịch sử mua hàng</a></li>
                                <li><a class="dropdown-item d-flex user-setting" href="../public/logout.php">
                                        Đăng xuất</a></li>

                            </ul>
                            <!-- <a class="nav-link" href="../public/logout.php">
                                
                                Đăng xuất
                            </a> -->

                        </li>
                    <?php
                    } else {
                        echo '<li class="nav-item test mx-2 ">
                                    <a class="nav-link text-dark" href="../public/login.php">
                                        <i class="bx bx-user text-dark"></i>
                                        Đăng nhập
                                    </a>               
                            </li>';
                    }
                    ?>
                    <li class="nav-item test mx-2">
                        <a class="nav-link header-action-link text-dark" href="../public/cartpage.php">
                            <i class="bx bx-cart box-icon text-dark "></i>
                            <!-- Thẻ để hiển thị số lượng sản phẩm -->
                            <?php
                            echo '<span class="count-holder ">' . $totalItems . '</span>';
                            ?>
                            <!-- <span class="count-holder"><?php $totalItems ?></span> -->
                            Giỏ hàng</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    </section>

    <style>
        .footer-logo {
            margin-bottom: 30px;
            }
        .footer-logo img {
                max-width: 200px;
        }
    </style>
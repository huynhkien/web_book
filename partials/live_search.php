<?php
require_once '../src/DBConnection.php';
$pdo = DBConnection::getConnection(); // Kết nối đến cơ sở dữ liệu

if (isset($_POST['input'])) {
    $input = $_POST['input'];
    $stmt = $pdo->prepare('SELECT * FROM product WHERE productName LIKE :input OR author LIKE :inputAuthor');
    $stmt->execute([
        ':input' => '%' . $input . '%',
        ':inputAuthor' => $input . '%'
    ]);


    if ($stmt->rowCount() > 0) { ?>
        <ul class="list-group mt-4">
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                <li class="list-group-item">
                    <a class="d-block stretched-link" href="../public/product.php?product_id=<?php echo $row['product_id']; ?>">
                        <?php echo $row['productName']; ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
<?php } else {
        echo "<h6 class='text-danger text-center'>Không có kết quả </h6>";
    }
}
?>
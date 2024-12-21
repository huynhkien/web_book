<?php
class Product
{
    private $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function getAllProducts()
    {
        $query = $this->db->query("SELECT product.*, catalog.catalogName 
                                    FROM product 
                                    INNER JOIN catalog ON product.catalog_id = catalog.catalog_id");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    //lấy theo catalog_id
    public function getProductByCatalogId($catalog_id)
    {
        $query = $this->db->prepare("SELECT product.*, catalog.catalogName 
                                    FROM product 
                                    INNER JOIN catalog ON product.catalog_id = catalog.catalog_id
                                    WHERE product.catalog_id = ?");
        $query->execute([$catalog_id]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    //Lấy 8 sp mới nhất
    public function getRecentProducts()
    {
        $query = $this->db->query("SELECT * FROM product ORDER BY product_id DESC");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductByProductId($product_id)
    {
        $query = $this->db->prepare("SELECT product.*, catalog.catalogName 
                                    FROM product 
                                    INNER JOIN catalog ON product.catalog_id = catalog.catalog_id
                                    WHERE product.product_id = ?");
        $query->execute([$product_id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function addProduct($productName, $catalog_id, $author, $productPrice, $description, $img, $quantity)
    {
        $query = $this->db->prepare("INSERT INTO product (productName, catalog_id, author, productPrice, description, img, quantity) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $query->execute([$productName, $catalog_id, $author, $productPrice, $description, $img, $quantity]);
    }

    public function updateProduct($product_id, $productName, $catalog_id, $author, $productPrice, $description, $img, $quantity)
    {
        $query = $this->db->prepare("UPDATE product SET productName = ?, catalog_id = ?, author = ?, productPrice = ?, description = ?, img = ?, quantity = ? WHERE product_id = ?");
        return $query->execute([$productName, $catalog_id, $author, $productPrice, $description, $img, $quantity, $product_id]);
    }

    public function deleteProduct($product_id)
    {
        $query = $this->db->prepare("DELETE FROM product WHERE product_id = ?");
        return $query->execute([$product_id]);
    }

    public function getProductImages($product_id)
    {
        $query = $this->db->prepare("SELECT * FROM imglist WHERE product_id = ?");
        $query->execute([$product_id]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addProductImages($product_id, $imagePaths)
    {
        $query = $this->db->prepare("INSERT INTO imglist (product_id, img_path) VALUES (?, ?)");
        foreach ($imagePaths as $imagePath) {
            $query->execute([$product_id, $imagePath]);
        }
    }

    public function deleteProductImages($product_id)
    {
        // Xóa tất cả ảnh phụ hiện tại của sản phẩm
        $deleteQuery = $this->db->prepare("DELETE FROM imglist WHERE product_id = ?");
        $deleteQuery->execute([$product_id]);
    }

    public function updateProductImage($product_id, $imagePaths)
    {
        //Xóa tất cả ảnh phụ hiện tại của sản phẩm
        $deleteQuery = $this->db->prepare("DELETE FROM imglist WHERE product_id = ?");
        $deleteQuery->execute([$product_id]);

        // Thêm các ảnh phụ mới vào cơ sở dữ liệu
        $insertQuery = $this->db->prepare("INSERT INTO imglist (product_id, img_path) VALUES (?, ?)");
        foreach ($imagePaths as $imagePath) {
            $insertQuery->execute([$product_id, $imagePath]);
        }
    }

    public function updateProductInfo($product_id, $postData, $mainImgFile, $additionalImages)
    {
        // Lấy dữ liệu từ form
        $productName = $postData["productName"];
        $catalog_id = $postData["catalog_id"];
        $author = $postData["author"];
        $productPrice = $postData["productPrice"];
        $description = $postData["description"];
        $quantity = $postData["quantity"];

        // Lấy thông tin sản phẩm hiện tại
        $product = $this->getProductByProductId($product_id);
        $mainImg = $product['img']; // Đường dẫn ảnh chính hiện tại
        // Lấy danh sách ảnh phụ hiện tại
        $currentAdditionalImages = $this->getProductImages($product_id);
        $additionalImagePaths = [];
        foreach ($currentAdditionalImages as $image) {
            $additionalImagePaths[] = $image['img_path'];
        }

        // Kiểm tra xem người dùng có tải lên ảnh mới không
        if (!empty($mainImgFile['tmp_name'])) {
            // Xử lý và di chuyển ảnh chính vào thư mục upload
            $uploadDirMain = '../assets/img/upload/';
            $uploadFileMain = $uploadDirMain . basename($mainImgFile['name']);
            move_uploaded_file($mainImgFile['tmp_name'], $uploadFileMain);
            $mainImg = $uploadFileMain;
        }

        // Xử lý và di chuyển ảnh phụ vào thư mục upload (nếu có)
        if (!empty($additionalImages['tmp_name'])) {
            // $additionalImagePaths = [];
            foreach ($additionalImages['tmp_name'] as $index => $tmp_name) {
                $uploadDirAdditional = '../assets/img/upload/';
                $uploadFileAdditional = $uploadDirAdditional . basename($additionalImages['name'][$index]);
                move_uploaded_file($tmp_name, $uploadFileAdditional);
                $additionalImagePaths[] = $uploadFileAdditional;
            }
        }

        // Cập nhật thông tin sản phẩm
        $this->updateProduct($product_id, $productName, $catalog_id, $author, $productPrice, $description, $mainImg, $quantity);
        // Cập nhật ảnh phụ vào bảng imglist
        $this->updateProductImage($product_id, $additionalImagePaths);
    }

    function getTotalNumberOfProduct()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM product");
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    function showCart($cartItems)
    {
        $totalAmount = 0;
        // Bắt đầu bảng
        echo "<table class='table align-middle mb-0 bg-white'>";
        echo "<thead class='bg-light'>";
        echo "<tr class='text-center'>";
        echo "<th scope='col' style='width: 30%;'>Ảnh</th>";
        echo "<th scope='col' style='width: 70%;'>Sản phẩm</th>";
        echo "<th scope='col' style='width: 10%;'>Đơn giá</th>";
        echo "<th scope='col' style='width: 5%;'>SL</th>";
        echo "<th scope='col' style='width: 10%;'>Tổng giá</th>";
        echo "<th scope='col' style='width: 5%;'>Lựa chọn</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody class='text-center'>";

        // Duyệt qua các mục trong giỏ hàng
        foreach ($cartItems as $item) {
            $product_id = $item['product_id'];
            $product = $this->getProductByProductId($product_id);
            if ($product) {
                // Hiển thị thông tin sản phẩm và số lượng từ session
                echo "<tr>";
                echo "<td><img src='" . $product['img'] . "' alt='Product Image'></td>";
                echo "<td>" . $product['productName'] . "</td>";
                echo "<td>" . number_format($product['productPrice'], 0, '.', '.') . "đ</td>";
                echo "<td>  <input type='number' class='form-control quantity-input' style='width:100px;' value='" . $item['quantity'] . "' id='quantity_" . $product_id . "' name='quantity'> </td>";

                // Tính tổng giá
                $totalPrice = $product['productPrice'] * $item['quantity'];
                echo "<td>" . number_format($totalPrice, 0, '.', '.') . "đ</td>";
                // Hiển thị các lựa chọn (nếu cần)
                echo "<td>
                        <a href='cart.php?action=remove&product_id=" . $item['product_id'] . "'>
                            <button class='btn btn-danger btn-block'>Xóa</button>
                        </a>
                        
                </td>";
                echo "</tr>";
            }
            $totalAmount = $totalAmount + $totalPrice;
        }
        // Kết thúc bảng
        echo "</tbody>";
        echo "<tfoot>";
        echo "<tr>";
        echo "<td colspan='4' class='text-start'><strong>Tổng tiền:</strong></td>";
        // Tính tổng tiền của giỏ hàng
        echo "<td class='text-end'><strong>" . number_format($totalAmount, 0, '.', '.') . "đ</strong></td>";
        echo "<td>
                <a href='cart.php?action=clearall'>
                    <button class='btn btn-warning btn-block'>Clear</button>
                </a>
                
            </td>";
        echo "</tr>";
        echo "</tfoot>";
        echo "</table>";
        return $totalAmount;
    }

    public function updateProductQuantity($product_id, $quantity)
    {
        $query = $this->db->prepare("UPDATE product SET quantity = quantity - ? WHERE product_id = ?");
        $query->execute([$quantity, $product_id]);
    }

    public function updateProductQuantityPlug($product_id, $quantity)
    {
        $query = $this->db->prepare("UPDATE product SET quantity = quantity + ? WHERE product_id = ?");
        $query->execute([$quantity, $product_id]);
    }

    public function getAllProductsCharacters($character_name)
    {
        $query = $this->db->prepare("SELECT * FROM product  WHERE productName LIKE ?");
        $query->execute(["%$character_name%"]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}

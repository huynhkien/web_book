<?php
class Category
{
    private $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function getAllCategory()
    {
        $query = $this->db->query("SELECT * 
                                    FROM catalog 
                                    ");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getCategory($catalog_id)
    {
        $query = $this->db->prepare("SELECT catalogName 
                                    FROM catalog 
                                    WHERE catalog_id = ?");
        $query->execute([$catalog_id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function addCategory($catalogName,$img){
        $query = $this->db->prepare("INSERT INTO catalog (catalogName, img) VALUES (?, ?)");
        return $query->execute([$catalogName,$img]);
    }
 
    public function updateCategory($catalog_id, $catalogName, $img){
        $query = $this->db->prepare("UPDATE catalog SET catalogName = ?, img = ? WHERE catalog_id = ?");
        return $query->execute([$catalogName, $img, $catalog_id]); 
    }
    
    
    public function deleteCategory($catalog_id){
        $query = $this->db->prepare("DELETE FROM catalog WHERE catalog_id =?");
        return $query->execute([$catalog_id]);
    }
    public function updateCategoryInfo($catalog_id, $postData, $mainImgFile)
    {
        // Lấy dữ liệu từ form
        $productName = $postData["catalogName"];
        // Lấy thông tin sản phẩm hiện tại
        $category = $this->getCategory($catalog_id);
        $mainImg = $category['img']; // Đường dẫn ảnh chính hiện tại
        // Lấy danh sách ảnh phụ hiện tại

        // Kiểm tra xem người dùng có tải lên ảnh mới không
        if (!empty($mainImgFile['tmp_name'])) {
            // Xử lý và di chuyển ảnh chính vào thư mục upload
            $uploadDirMain = '../assets/img/upload/';
            $uploadFileMain = $uploadDirMain . basename($mainImgFile['name']);
            move_uploaded_file($mainImgFile['tmp_name'], $uploadFileMain);
            $mainImg = $uploadFileMain;
        }
       

        // Cập nhật thông tin sản phẩm
        $this->updateCategory($catalog_id, $productName, $mainImg);
    }
   
}

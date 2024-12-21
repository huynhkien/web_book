<?php
class Bill
{
    private $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function getAllBill()
    {
        $query = $this->db->query("SELECT bill.*, users.name, users.email, users.phone, users.address
                                    FROM bill
                                    INNER JOIN users ON bill.user_id = users.user_id");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllBillByBillId($bill_id)
    {
        $query = $this->db->prepare("SELECT bill.*, users.name, users.email, users.phone, users.address
                                    FROM bill
                                    INNER JOIN users ON bill.user_id = users.user_id
                                    WHERE bill.bill_id = ?");
        $query->execute([$bill_id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }


    public function getAllBillByUserId($user_id)
    {
        $query = $this->db->prepare("SELECT bill.*, users.name, users.email, users.phone, users.address
                                FROM bill
                                INNER JOIN users ON bill.user_id = users.user_id
                                WHERE bill.user_id = ?");
        $query->execute([$user_id]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }


    public function confirmOrder($bill_id)
    {
        $query = $this->db->prepare("UPDATE bill SET status = 'Đã xác nhận' WHERE bill_id = ?");
        $query->execute([$bill_id]);
        return $query->rowCount(); // Trả về số dòng đã được cập nhật
    }

    public function notConfirmOrder($bill_id)
    {
        $query = $this->db->prepare("UPDATE bill SET status = 'Chưa xác nhận' WHERE bill_id = ?");
        $query->execute([$bill_id]);
        return $query->rowCount(); // Trả về số dòng đã được cập nhật
    }

    public function markOrderDelivered($bill_id)
    {
        $query = $this->db->prepare("UPDATE bill SET status = 'Đã giao hàng' WHERE bill_id = ?");
        $query->execute([$bill_id]);
        return $query->rowCount(); // Trả về số dòng đã được cập nhật
    }

    public function billReturn($bill_id)
    {
        $query = $this->db->prepare("UPDATE bill SET status = 'Đã hủy đơn' WHERE bill_id = ?");
        $query->execute([$bill_id]);
        return $query->rowCount(); // Trả về số dòng đã được cập nhật
    }

    public function deleteBill($bill_id)
    {
        // Chuẩn bị truy vấn SQL để xóa đơn hàng
        $query = $this->db->prepare("DELETE FROM bill WHERE bill_id = ?");
        $query->execute([$bill_id]);
    }

    public function getBillDetail($bill_id)
    {
        $query = $this->db->prepare("SELECT bill_details.*, product.* FROM bill_details
                                    INNER JOIN product ON bill_details.product_id = product.product_id
                                    WHERE bill_details.bill_id = ?");
        $query->execute([$bill_id]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalAmountOfAllBills()
    {
        // Chuẩn bị truy vấn SQL để tính tổng số tiền của tất cả các đơn hàng
        $query = $this->db->query("SELECT SUM(total_amount) AS total_amount FROM bill");
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['total_amount'];
    }

    function getTotalNumberOfOrders()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM bill");
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function createBill($user_id, $totalAmount, $notes)
    {
        $query = $this->db->prepare("INSERT INTO bill (user_id, total_amount, notes) VALUES (?, ?, ?)");
        $query->execute([$user_id, $totalAmount, $notes]);
        // Trả về bill_id của bản ghi vừa được thêm vào
        return $this->db->lastInsertId();
    }

    public function addBillDetail($bill_id, $product_id, $bill_quantity)
    {
        $query = $this->db->prepare("INSERT INTO bill_details (bill_id, product_id, bill_quantity) VALUES (?, ?, ?)");
        $query->execute([$bill_id, $product_id, $bill_quantity]);
    }

    public function getAnalysisDailys()
    {
        // Truy vấn cơ sở dữ liệu để lấy dữ liệu Ngày từ bảng bill
        $query = $this->db->query("SELECT DATE(bill_date) AS ngay, SUM(total_amount) AS tong_doanh_thu FROM bill GROUP BY DATE(bill_date)");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAnalysisMonthly()
    {
        // Truy vấn cơ sở dữ liệu để lấy dữ liệu Ngày từ bảng bill
        $query = $this->db->query("SELECT MONTH(bill_date) AS ngay, SUM(total_amount) AS tong_doanh_thu FROM bill GROUP BY MONTH(bill_date)");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}

<?php
class User
{
    private $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function getAllUser()
    {
        $query = $this->db->query("SELECT users.*, roles.role_name
                                    FROM users
                                    INNER JOIN roles ON users.role_id = roles.role_id
                                    WHERE users.role_id = 1");
        // $query->execute([1]); //chỉ lấy khách hàng
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getUserByUserId($user_id)
    {
        $query = $this->db->prepare("SELECT users.*, roles.role_name 
                                    FROM users 
                                    INNER JOIN roles ON users.role_id = roles.role_id
                                    WHERE users.user_id = ?");
        $query->execute([$user_id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function isAdmin($user_id)
    {
        $query = $this->db->prepare("SELECT role_id FROM users WHERE user_id = ?");
        $query->execute([$user_id]);
        $role = $query->fetchColumn();

        return ($role == 2); // Nếu role_id là 2 (admin) thì trả về true, ngược lại trả về false
    }

    public function addUser($email, $password, $name, $address, $phone)
    {
        // Kiểm tra xem người dùng đã tồn tại hay chưa
        if ($this->isUserExistsByEmail($email)) {
            return false;
        }

        // Hash mật khẩu trước khi lưu vào cơ sở dữ liệu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Thêm người dùng mới vào cơ sở dữ liệu
        $query = $this->db->prepare("INSERT INTO users (email, password,  name, address, phone) VALUES (?, ?, ?, ?, ?)");
        $query->execute([$email, $hashed_password, $name, $address, $phone]);

        return true;
    }

    public function isUserExistsByEmail($email)
    {
        $query = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $query->execute([$email]);
        $count = $query->fetchColumn();

        return ($count > 0); // Trả về true nếu người dùng đã tồn tại, ngược lại trả về false
    }

    public function login($email, $password)
    {
        // Kiểm tra xem người dùng có tồn tại trong cơ sở dữ liệu không
        $query = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $query->execute([$email]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        // Nếu có người dùng, thực hiện đăng nhập
        if ($user && password_verify($password, $user['password'])) {
            // Lưu thông tin người dùng vào session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role_id'] = $user['role_id'];

            // Kiểm tra nếu là admin, chuyển hướng đến trang admin.php
            if ($this->isAdmin($user['user_id'])) {
                header('Location: admin_dash_board.php');
                exit;
            } else {
                // Ngược lại, chuyển hướng đến trang chính
                header('Location: ../index.php');
                exit;
            }
        } else {
            // return false;
            header('Location: login.php');
            exit;
        }
    }

    public function logout()
    {
        session_start();
        // Xóa tất cả các biến session
        $_SESSION = array();
        // Hủy phiên đăng nhập
        session_destroy();
    }

    public function updateUser($user_id, $postData)
    {
        // Kiểm tra xem người dùng có tồn tại không
        $existingUser = $this->getUserByUserId($user_id);
        if (!$existingUser) {
            return false; // Người dùng không tồn tại
        }

        // Lấy dữ liệu từ form
        $email = $postData["email"];
        $name = $postData["name"];
        $address = $postData["address"];
        $phone = $postData["phone"];

        // // Hash mật khẩu mới nếu được cung cấp
        // if (!empty($password)) {
        //     $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // } else {
        //     // Nếu không có mật khẩu mới, sử dụng mật khẩu cũ
        //     $hashedPassword = $existingUser['password'];
        // }

        // Kiểm tra xem email mới có trùng với email của người dùng khác không
        $query = $this->db->prepare("SELECT * FROM users WHERE email = ? AND user_id != ?");
        $query->execute([$email, $user_id]);
        $duplicateEmail = $query->fetch(PDO::FETCH_ASSOC);
        if ($duplicateEmail) {
            return 'duplicate_email'; // Email đã được sử dụng bởi người dùng khác
        }

        // Cập nhật thông tin người dùng vào cơ sở dữ liệu
        $query = $this->db->prepare("UPDATE users SET email = ?, name = ?, address = ?, phone = ? WHERE user_id = ?");
        $result = $query->execute([$email, $name, $address, $phone, $user_id]);

        return $result ? true : false; // Trả về true nếu cập nhật thành công, ngược lại trả về false
    }

    public function updatePassword($user_id, $newPassword)
    {
        // Kiểm tra xem người dùng có tồn tại không
        $existingUser = $this->getUserByUserId($user_id);
        if (!$existingUser) {
            return false; // Người dùng không tồn tại
        }

        // Hash mật khẩu mới
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Cập nhật mật khẩu mới vào cơ sở dữ liệu
        $query = $this->db->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $result = $query->execute([$user_id, $hashedPassword]);

        return $result ? true : false; // Trả về true nếu cập nhật thành công, ngược lại trả về false
    }


    public function deleteUser($user_id)
    {
        $query = $this->db->prepare("DELETE FROM users WHERE user_id = ?");
        return $query->execute([$user_id]);
    }

    function getTotalNumberOfUsers()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM users");
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}

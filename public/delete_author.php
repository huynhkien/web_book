<?php
require_once '../src/DBConnection.php';
require_once '../src/Author.php';

$pdo = DBConnection::getConnection();
$authorManager = new Author($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    // Lấy author_id từ form
    $author_id = $_POST["author_id"];
    $authorManager->deleteAuthor($author_id);
    header("Location: show_author.php");
    exit();
} else {
    // Nếu không phải là phương thức POST hoặc không có tham số "delete" trong POST, chuyển hướng về trang chính
    header("Location: show_author.php");
    exit();
}
?>

<?php
class DBConnection
{
    public static function getConnection()
    {
        // Code để tạo kết nối PDO
        $pdo = new PDO('mysql:host=localhost;dbname=book_shop', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}

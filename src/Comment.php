<?php
class Comment
{
    private $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

      public function addComment($id_product, $name, $comment)
    {
        $query = $this->db->prepare("INSERT INTO coment (id_product, name_coment, text_coment) VALUES (?, ?, ?)");
        return $query->execute([$id_product, $name, $comment]);
    }
      
      public function getComment($id_product)
    {
        $query = $this->db->prepare("SELECT * FROM coment WHERE id_product = ?");
        $query->execute([$id_product]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
        
    
   
}

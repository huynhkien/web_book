<?php
class Author
{
    private $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function getAllAuthor()
    {
        $query = $this->db->query("SELECT * 
                                    FROM author 
                                    ");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAuthor($author_id)
    {
        $query = $this->db->prepare("SELECT authorName 
                                    FROM author 
                                    WHERE author_id = ?");
        $query->execute([$author_id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function addAuthor($authorName){
        $query = $this->db->prepare("INSERT INTO author (authorName) VALUES (?)");
        return $query->execute([$authorName]);
    }
    public function updateAuthor($author_id, $authorName){
        $query = $this->db->prepare("UPDATE author SET authorName=? WHERE author_id=? ");
        return $query->execute([$authorName, $author_id]);
    }
    
    public function deleteAuthor($author_id){
        $query = $this->db->prepare("DELETE FROM author WHERE author_id =?");
        return $query->execute([$author_id]);
    }
   
}

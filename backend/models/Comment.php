<?php


class Comment {
    private $conn;
    private $table_name = 'comments';

    public $id;
    public $article_id;
    public $user_id;
    public $comment_text;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (article_id, user_id, comment_text) 
                  VALUES (:article_id, :user_id, :comment_text)";

        $stmt = $this->conn->prepare($query);

        
        $this->article_id = htmlspecialchars(strip_tags($this->article_id));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->comment_text = htmlspecialchars(strip_tags($this->comment_text));

        
        $stmt->bindParam(':article_id', $this->article_id);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':comment_text', $this->comment_text);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Получение комментариев по идентификатору статьи
    public function readByArticleId() {
        $query = "SELECT c.*, u.name as user_name FROM " . $this->table_name . " c
                  JOIN users u ON c.user_id = u.id
                  WHERE article_id = :article_id ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);

        $this->article_id = htmlspecialchars(strip_tags($this->article_id));

        $stmt->bindParam(':article_id', $this->article_id);

        $stmt->execute();

        return $stmt;
    }

    
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET comment_text = :comment_text
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->comment_text = htmlspecialchars(strip_tags($this->comment_text));

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':comment_text', $this->comment_text);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>

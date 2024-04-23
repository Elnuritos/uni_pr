<?php


class Article
{
    private $conn;
    private $table_name = 'articles';

    public $id;
    public $user_id;
    public $title;
    public $text;
    public $image;
    public $created_at;
    public $updated_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  (user_id, title, text, image) 
                  VALUES (:user_id, :title, :text, :image)";

        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->text = htmlspecialchars(strip_tags($this->text));
        $this->image = htmlspecialchars(strip_tags($this->image));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':text', $this->text);
        $stmt->bindParam(':image', $this->image);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    

    
    public function readOne()
    {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE id = :id LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->title = $row['title'];
            $this->text = $row['text'];
            $this->image = $row['image'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];

            return $row;
        }
        return null;
    }


    public function update()
    {
        $query = "UPDATE " . $this->table_name . " 
              SET title = :title, text = :text, image = :image, updated_at = NOW()
              WHERE id = :id";

        $stmt = $this->conn->prepare($query);


        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->text = htmlspecialchars(strip_tags($this->text));
        $this->image = htmlspecialchars(strip_tags($this->image));

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':text', $this->text);
        $stmt->bindParam(':image', $this->image);


        if ($stmt->execute()) {
            return true;
        }
        return false;
    }



    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);


        $this->id = htmlspecialchars(strip_tags($this->id));


        $stmt->bindParam(':id', $this->id);


        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function getArticlesByUserId($user_id) {
        $query = "SELECT id, title, text, image, updated_at FROM " . $this->table_name . " WHERE user_id = :user_id ORDER BY updated_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

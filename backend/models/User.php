<?php


class User {
    private $conn;
    private $table_name = 'users';

    public $id;
    public $name;
    public $email;
    public $password;
    public $about_me;

    public function __construct($db) {
        $this->conn = $db;
    }

  
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (name, email, password, about_me) VALUES (:name, :email, :password, :about_me)";

        $stmt = $this->conn->prepare($query);

        // Очистка данных
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->about_me = htmlspecialchars(strip_tags($this->about_me));

        // Хеширование пароля
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);

        // Привязка значений
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':about_me', $this->about_me);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Получение пользователя по email
    public function readByEmail() {
        $query = "SELECT id, name, email, about_me,password FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->about_me = $row['about_me'];
            $this->password=$row['password'];
            return $row;
        }
        return null;
    }

    // Обновление информации о пользователе
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET name = :name, email = :email, about_me = :about_me
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->about_me = htmlspecialchars(strip_tags($this->about_me));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':about_me', $this->about_me);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Удаление пользователя
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
    public function getUserById($user_id) {
        $query = "SELECT id, name, email, about_me FROM " . $this->table_name . " WHERE id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}
?>


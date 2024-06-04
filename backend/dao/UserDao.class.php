<?php
require_once __DIR__ . '/BaseDao.class.php';

class UserDao extends BaseDao
{

    private $table = 'users';
    public function __construct()
    {
        parent::__construct($this->table);
    }

    // CRUD operations
    public function createUser($data)
    {
        return $this->insert($this->table, $data);
    }

    public function findByEmail($email)
    {
        return $this->query_unique("SELECT * FROM " . $this->table . " WHERE email = :email", ['email' => $email]);
    }

    public function updateUser($id, $data)
    {
        $this->execute_update($this->table, $id, $data);
    }

    public function deleteUser($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $this->execute($query, ['id' => $id]);
    }
    public function getUserById($id)
    {
        return $this->query_unique("SELECT * FROM " . $this->table . " WHERE id = :id", ['id' => $id]);
    }
}

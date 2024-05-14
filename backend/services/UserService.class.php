<?php
require_once __DIR__ . '/../dao/UserDao.class.php';

class UserService
{
    private $userDao;

    public function __construct()
    {
        $this->userDao = new UserDao();
    }

    public function createUser($data)
    {
        return $this->userDao->createUser($data);
    }

    public function getUserByEmail($email)
    {
        return $this->userDao->findByEmail($email);
    }

    public function updateUser($id, $data)
    {
        $this->userDao->updateUser($id, $data);
    }

    public function deleteUser($id)
    {
        $this->userDao->deleteUser($id);
    }
}

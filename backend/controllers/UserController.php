<?php


require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ .'/ApiResponseHandler.php';
class UserController
{
    private $userModel;

    public function __construct()
    {
        $database = Database::getInstance();
        $db = $database->getConnection();
        $this->userModel = new User($db);
    }

    public function createUser($name, $email, $password, $about_me)
    {
        $this->userModel->name = $name;
        $this->userModel->email = $email;
        $this->userModel->password = $password;
        $this->userModel->about_me = $about_me;

        if ($this->userModel->create()) {
            echo json_encode(array('message' => 'User Created'));
        } else {
            echo json_encode(array('message' => 'User Not Created'));
        }
    }

    public function getUserByEmail($email)
    {
        $this->userModel->email = $email;
        $user = $this->userModel->readByEmail();
        echo json_encode($user);
    }

    public function updateUser($id, $name, $email, $about_me)
    {
        $this->userModel->id = $id;
        $this->userModel->name = $name;
        $this->userModel->email = $email;
        $this->userModel->about_me = $about_me;

        if ($this->userModel->update()) {
            echo json_encode(array('message' => 'User Updated'));
        } else {
            echo json_encode(array('message' => 'User Not Updated'));
        }
    }

    public function deleteUser($id)
    {
        $this->userModel->id = $id;

        if ($this->userModel->delete()) {
            echo json_encode(array('message' => 'User Deleted'));
        } else {
            echo json_encode(array('message' => 'User Not Deleted'));
        }
    }
    public function signup()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        $name = $data['name'];
        $email = $data['email'];
        $password = $data['password'];
        $about_me = $data['aboutMe'];

        if (!$this->userModel->readByEmail($email)) {
            $this->userModel->name = $name;
            $this->userModel->email = $email;
            $this->userModel->password = $password;
            $this->userModel->about_me = $about_me;

            if ($this->userModel->create()) {
                http_response_code(201); // Created
                echo json_encode(array('message' => 'User Created Successfully'));
            } else {
                http_response_code(400); // Bad Request
                echo json_encode(array('message' => 'User Not Created'));
            }
        } else {
            http_response_code(409); // Conflict
            echo json_encode(array('message' => 'Email already in use'));
        }
    }
    public function login()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        $password = $data['password'];
        $email = $data['email'];
        $this->userModel->email = $email;
        $user = $this->userModel->readByEmail();

        if ($user) {
            // Проверка пароля
            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id']; 
                echo json_encode(array('message' => 'Login Successful', 'user' => $user));
            } else {

                echo json_encode(array('message' => 'Login Failed: Incorrect Password'));
            }
        } else {

            echo json_encode(array('message' => 'Login Failed: User Not Found'));
        }
    }
    public function logout()
    {
       
        $_SESSION = array();

      
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        echo json_encode(array('message' => 'Logged out successfully'));
    }
    public function getUserDetails() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_id'])) {
           
            $userDetails = $this->userModel->getUserById($_SESSION['user_id']);
            echo json_encode($userDetails);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'User not found']);
        }
    }
    
}

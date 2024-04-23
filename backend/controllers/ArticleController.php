<?php


require_once __DIR__ . '/../models/Article.php';
require_once __DIR__ . '/../config/Database.php';

class ArticleController
{
    private $articleModel;

    public function __construct()
    {
        $database = Database::getInstance();
        $db = $database->getConnection();
        $this->articleModel = new Article($db);
    }

    public function createArticle()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
      
        $data = json_decode(file_get_contents("php://input"), true);
        $this->articleModel->title = $data['title'];
        $this->articleModel->text = $data['text'];
        $this->articleModel->user_id =$_SESSION['user_id'];

        if ($this->articleModel->create()) {
            echo json_encode(array('message' => 'Article Created'));
        } else {
            echo json_encode(array('message' => 'Article Not Created'));
        }
    }

    public function getArticle()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'];

        $this->articleModel->id = $id;
        $article = $this->articleModel->readOne();
        if ($article) {
            echo json_encode($article);
        } else {
            http_response_code(404);
            echo json_encode(array('message' => 'Article Not Found'));
        }
    }


    public function updateArticle()
    {

        $data = json_decode(file_get_contents("php://input"), true);
        $this->articleModel->id = $data['id'];
        $this->articleModel->title = $data['title'];
        $this->articleModel->text = $data['text'];

        if ($this->articleModel->update()) {
            echo json_encode(array('message' => 'Article Updated'));
        } else {
            echo json_encode(array('message' => 'Article Not Updated'));
        }
    }

    public function deleteArticle($id)
    {
        $this->articleModel->id = $id;

        if ($this->articleModel->delete()) {
            echo json_encode(array('message' => 'Article Deleted'));
        } else {
            echo json_encode(array('message' => 'Article Not Deleted'));
        }
    }
    public function getAllArticles()
    {
        $articles = $this->articleModel->read();
        echo json_encode($articles);
    }
    public function getUserArticles() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_id'])) {
            $articles = $this->articleModel->getArticlesByUserId($_SESSION['user_id']);
            echo json_encode($articles);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'User not authenticated']);
        }
    }
    
}

<?php
require_once __DIR__ . '/../dao/ArticleDao.class.php';

class ArticleService {
    private $articleDao;

    public function __construct() {
        $this->articleDao = new ArticleDao();
    }

    public function createArticle($data) {
        return $this->articleDao->createArticle($data);
    }

    public function getAllArticles($offset, $limit, $order) {
        return $this->articleDao->getAllArticles($offset, $limit, $order);
    }

    public function getArticleById($id) {
        return $this->articleDao->getArticleById($id);
    }

    public function updateArticle($id, $data) {
        return $this->articleDao->updateArticle($id, $data);
    }

    public function deleteArticle($id) {
        return $this->articleDao->deleteArticle($id);
    }

    public function getArticlesByUserId($userId) {
        return $this->articleDao->getArticlesByUserId($userId);
    }
}
?>

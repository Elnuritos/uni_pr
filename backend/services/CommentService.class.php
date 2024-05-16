<?php
require_once __DIR__ . '/../dao/CommentDao.class.php';

class CommentService {
    private $commentDao;

    public function __construct() {
        $this->commentDao = new CommentDao();
    }

    public function createComment($data) {
        return $this->commentDao->createComment($data);
    }

    public function getCommentsByArticleId($articleId) {
        return $this->commentDao->getCommentsByArticleId($articleId);
    }

    public function updateComment($id, $commentText) {
        return $this->commentDao->updateComment($id, ['comment_text' => htmlspecialchars(strip_tags($commentText))]);
    }

    public function deleteComment($id) {
        return $this->commentDao->deleteComment($id);
    }
}
?>

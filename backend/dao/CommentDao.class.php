<?php
require_once __DIR__ . '/BaseDao.class.php';

class CommentDao extends BaseDao {
    private $table='comments';
    public function __construct() {
        parent::__construct("comments");
    }

    public function createComment($data) {
        return $this->insert($this->table,$data);
    }

    public function getCommentsByArticleId($articleId) {
        return $this->query("SELECT c.*, u.name as user_name FROM " . $this->table . " c JOIN users u ON c.user_id = u.id WHERE article_id = :article_id ORDER BY created_at DESC", ['article_id' => $articleId]);
    }

    public function updateComment($id, $data) {
        return $this->execute_update($this->table,$id, $data);
    }

    public function deleteComment($id) {
        return $this->execute("DELETE FROM " . $this->table . " WHERE id = :id", ['id' => $id]);
    }
}
?>

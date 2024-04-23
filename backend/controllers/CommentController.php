<?php

require_once __DIR__.'/../models/Comment.php';
require_once __DIR__.'/../config/Database.php';

class CommentController {
    private $commentModel;

    public function __construct() {
        $database = Database::getInstance();
        $db = $database->getConnection();
        $this->commentModel = new Comment($db);
    }

    public function createComment($article_id, $user_id, $comment_text) {
        $this->commentModel->article_id = $article_id;
        $this->commentModel->user_id = $user_id;
        $this->commentModel->comment_text = $comment_text;

        if ($this->commentModel->create()) {
            echo json_encode(array('message' => 'Comment Created'));
        } else {
            echo json_encode(array('message' => 'Comment Not Created'));
        }
    }

    public function getCommentsByArticle($article_id) {
        $this->commentModel->article_id = $article_id;
        $comments = $this->commentModel->readByArticleId();
        $comment_arr = array();
        $comment_arr['data'] = array();

        while ($row = $comments->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $comment_item = array(
                'id' => $id,
                'article_id' => $article_id,
                'user_id' => $user_id,
                'user_name' => $user_name,
                'comment_text' => $comment_text,
                'created_at' => $created_at
            );
            array_push($comment_arr['data'], $comment_item);
        }

        echo json_encode($comment_arr);
    }

    public function updateComment($id, $comment_text) {
        $this->commentModel->id = $id;
        $this->commentModel->comment_text = $comment_text;

        if ($this->commentModel->update()) {
            echo json_encode(array('message' => 'Comment Updated'));
        } else {
            echo json_encode(array('message' => 'Comment Not Updated'));
        }
    }

    public function deleteComment($id) {
        $this->commentModel->id = $id;

        if ($this->commentModel->delete()) {
            echo json_encode(array('message' => 'Comment Deleted'));
        } else {
            echo json_encode(array('message' => 'Comment Not Deleted'));
        }
    }
}
?>

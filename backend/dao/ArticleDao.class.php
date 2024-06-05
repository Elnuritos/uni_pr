<?php
require_once __DIR__ . '/BaseDao.class.php';

class ArticleDao extends BaseDao
{

    private $table = 'articles';
    public function __construct()
    {
        parent::__construct("articles");
    }

    public function createArticle($data,$user_id)
    {
    
        return $this->insert($this->table, [
            'user_id' => htmlspecialchars(strip_tags($user_id)),
            'title' => htmlspecialchars(strip_tags($data['title'])),
            'text' => htmlspecialchars(strip_tags($data['text'])),
            'image' => htmlspecialchars(strip_tags($data['image'])),
            
        ]);
    }

    public function getAllArticles($offset = 0, $limit = 25, $order = "-id")
    {

        return $this->get_all($offset, $limit, $order);
    }

    public function getArticleById($id)
    {
        return $this->get_by_id($id);
    }

    public function updateArticle($id, $data,$imagePath=null)
    {
        
        $articleData = [
            'title' => htmlspecialchars(strip_tags($data['title'])),
            'text' => htmlspecialchars(strip_tags($data['text']))
        ];
    
        if ($imagePath) {
            $articleData['image'] = htmlspecialchars(strip_tags($imagePath));
        }
    
        return $this->update($id, $articleData);
    }

    public function deleteArticle($id)
    {
        // Simplify the delete operation
        $this->execute("DELETE FROM " . $this->table . " WHERE id = :id", ['id' => $id]);
    }

    public function getArticlesByUserId($user_id)
    {
        // Use the generic query method from BaseDao
        return $this->query("SELECT * FROM " . $this->table . " WHERE user_id = :user_id ORDER BY updated_at DESC", ['user_id' => $user_id]);
    }
}

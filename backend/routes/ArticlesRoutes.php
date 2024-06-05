<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../services/ArticleService.class.php';
Flight::group('/articles', function () {

    /**
     * @OA\Post(
     *     path="/articles/add",
     *     tags={"articles"},
     *     summary="Create a new article",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"title", "content", "author_id"},
     *             @OA\Property(property="title", type="string", example="New Article"),
     *             @OA\Property(property="content", type="string", example="Article content here"),
     *             @OA\Property(property="author_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Article created successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Failed to create article"
     *     )
     * )
     */
    Flight::route('POST /createArticle', function () {
        $data = Flight::request()->data->getData();
        $files = Flight::request()->files;
        $authHeader = Flight::request()->getHeader("Authorization");
        if (!$authHeader) {
            Flight::json(['error' => 'Authorization header is missing'], 401);
            return false;
        }

        $parts = explode(" ", $authHeader);
        if (count($parts) < 2 || strtolower($parts[0]) !== 'bearer') {
            Flight::json(['error' => 'Authorization header must be Bearer token'], 401);
            return false;
        }

        $token = $parts[1];

        $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
        $user_id = $decoded_token->user->id;
        if (isset($files['image'])) {
            $image = $files['image'];
            $imageName = basename($image['name']);
            $physicalPath = __DIR__ . '/../img/' . $imageName; // Физический путь на сервере
            $relativePath = 'backend/img/' . $imageName; // Относительный путь для клиента

            move_uploaded_file($image['tmp_name'], $physicalPath);
            $data['image'] = $relativePath;
        } else {
            $data['image'] = null;
        }
        $articleService = new ArticleService();
        if ($articleService->createArticle($data, $user_id)) {
            Flight::json(['message' => 'Article created successfully'], 201);
        } else {
            Flight::json(['message' => 'Article could not be created'], 400);
        }
    });

    /**
     * @OA\Put(
     *     path="/articles/update/{article_id}",
     *     tags={"articles"},
     *     summary="Update an existing article",
     *     @OA\Parameter(
     *         name="article_id",
     *         in="path",
     *         required=true,
     *         description="Article ID to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"title", "content"},
     *             @OA\Property(property="title", type="string", example="Updated Title"),
     *             @OA\Property(property="content", type="string", example="Updated content here")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article updated successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Failed to update article"
     *     )
     * )
     */
    Flight::route('POST /updateArticle/@article_id', function ($article_id) {
        $files = Flight::request()->files;
        $data = Flight::request()->data->getData();
        $articleService = new ArticleService();
        $imagePath = null;

        if (isset($files['image'])) {
            $image = $files['image'];
            $imageName = basename($image['name']);
            $physicalPath = __DIR__ . '/../img/' . $imageName;
            $relativePath = 'backend/img/' . $imageName;

            if (move_uploaded_file($image['tmp_name'], $physicalPath)) {
                $imagePath = $relativePath;
            } else {
                error_log("Error moving uploaded file");
            }
        }

        $success = $articleService->updateArticle($article_id, $data, $imagePath);
        
        if ($success) {
            $json_response = json_encode(['message' => 'Article updated successfully']);
            echo $json_response;
        } else {
            $json_response = json_encode(['message' => 'Article could not be updated']);
            http_response_code(400); // Установка HTTP статуса 400
            echo $json_response; // Ответ при ошибке
        }
        exit;
    });


    /**
     * @OA\Delete(
     *     path="/articles/delete/{article_id}",
     *     tags={"articles"},
     *     summary="Delete an article",
     *     @OA\Parameter(
     *         name="article_id",
     *         in="path",
     *         required=true,
     *         description="Article ID to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Failed to delete article"
     *     )
     * )
     */
    Flight::route('DELETE /delete/@article_id', function ($article_id) {
        $articleService = new ArticleService();
        if ($articleService->deleteArticle($article_id)) {
            Flight::json(['message' => 'Article deleted successfully']);
        } else {
            Flight::json(['message' => 'Article could not be deleted'], 400);
        }
    });

    /**
     * @OA\Get(
     *     path="/articles",
     *     tags={"articles"},
     *     summary="Get all articles",
     *     @OA\Response(
     *         response=200,
     *         description="A list of articles",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(type="object", 
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Sample Article"),
     *                 @OA\Property(property="content", type="string", example="Content here"),
     *                 @OA\Property(property="author_id", type="integer", example=1)
     *             )
     *         )
     *     )
     * )
     */
    Flight::route('GET /', function () {

        $offset = Flight::request()->query['offset'] ?? 0;
        $limit = Flight::request()->query['limit'] ?? 25;
        $order = Flight::request()->query['order'] ?? '-id';
        $articleService = new ArticleService();
        $articles = $articleService->getAllArticles($offset, $limit, $order);
        $json_response = json_encode($articles);
        echo $json_response;
        exit;
    });

    /**
     * @OA\Get(
     *     path="/articles/{article_id}",
     *     tags={"articles"},
     *     summary="Get a single article by ID",
     *     @OA\Parameter(
     *         name="article_id",
     *         in="path",
     *         required=true,
     *         description="The ID of the article to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A single article",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Sample Article"),
     *             @OA\Property(property="content", type="string", example="Content here"),
     *             @OA\Property(property="author_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found"
     *     )
     * )
     */
    Flight::route('GET /getArticle/@article_id', function ($article_id) {
        $articleService = new ArticleService();
        $article = $articleService->getArticleById($article_id);
        if ($article) {
            $json_response = json_encode($article);
            echo $json_response;
        } else {
            $json_response = json_encode(['message' => 'Article not found']);
            http_response_code(404);
            echo $json_response;
        }
        exit;
    });

    /**
     * @OA\Get(
     *     path="/articles/user/{user_id}",
     *     tags={"articles"},
     *     summary="Get articles by user ID",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         description="The ID of the user whose articles to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of articles by the specified user",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="User Article"),
     *                 @OA\Property(property="content", type="string", example="Content written by user"),
     *                 @OA\Property(property="author_id", type="integer", example=1)
     *             )
     *         )
     *     )
     * )
     */
    Flight::route('GET /getUserArticles', function () {
        $authHeader = Flight::request()->getHeader("Authorization");
        if (!$authHeader) {
            Flight::json(['error' => 'Authorization header is missing'], 401);
            return false;
        }

        $parts = explode(" ", $authHeader);
        if (count($parts) < 2 || strtolower($parts[0]) !== 'bearer') {
            Flight::json(['error' => 'Authorization header must be Bearer token'], 401);
            return false;
        }

        $token = $parts[1];

        $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
        $user_id = $decoded_token->user->id;
        $articleService = new ArticleService();
        $articles = $articleService->getArticlesByUserId($user_id);
        if ($articles) {
            $json_response = json_encode($articles);
            echo $json_response;
        } else {
            $json_response = json_encode(['message' => 'Articles not found']);
            http_response_code(404);
            echo $json_response;
        }
        exit;
    });
});

<?php

require_once __DIR__ . '/../services/CommentService.class.php';

/**
 *      @OA\Components(
 *         @OA\Schema(
 *             schema="Comment",
 *             type="object",
 *             required={"id", "article_id", "user_id", "comment_text"},
 *             @OA\Property(
 *                 property="id",
 *                 type="integer",
 *                 format="int64",
 *                 description="The unique identifier of the comment"
 *             ),
 *             @OA\Property(
 *                 property="article_id",
 *                 type="integer",
 *                 format="int64",
 *                 description="The ID of the article the comment belongs to"
 *             ),
 *             @OA\Property(
 *                 property="user_id",
 *                 type="integer",
 *                 format="int64",
 *                 description="The ID of the user who made the comment"
 *             ),
 *             @OA\Property(
 *                 property="comment_text",
 *                 type="string",
 *                 description="The content of the comment"
 *             )
 *         )
 *      )
 * 
 */
Flight::group('/comments', function() {
     /**
     * @OA\Post(
     *     path="/comments",
     *     tags={"comments"},
     *     summary="Create a new comment",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"article_id", "user_id", "comment_text"},
     *             @OA\Property(property="article_id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="comment_text", type="string", example="This is a great article!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment created successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error: Comment could not be created"
     *     )
     * )
     */
    Flight::route('POST /', function() {
        $data = Flight::request()->data->getData();
        $commentService = new CommentService();
        Flight::json($commentService->createComment($data));
    });
 /**
     * @OA\Get(
     *     path="/comments/article/{article_id}",
     *     tags={"comments"},
     *     summary="Retrieve all comments for a specific article",
     *     @OA\Parameter(
     *         name="article_id",
     *         in="path",
     *         required=true,
     *         description="The ID of the article",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval of comments",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Comment")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No comments found for the specified article"
     *     )
     * )
     */
    Flight::route('GET /article/@article_id', function($article_id) {
        $commentService = new CommentService();
        Flight::json($commentService->getCommentsByArticleId($article_id));
    });
    /**
     * @OA\Put(
     *     path="/comments/{id}",
     *     tags={"comments"},
     *     summary="Update a comment",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the comment to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"comment_text"},
     *             @OA\Property(property="comment_text", type="string", example="Updated comment text")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment updated successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error: Comment could not be updated"
     *     )
     * )
     */

    Flight::route('PUT /@id', function($id) {
        $data = Flight::request()->data->getData();
        $commentService = new CommentService();
        Flight::json($commentService->updateComment($id, $data['comment_text']));
    });

    /**
     * @OA\Delete(
     *     path="/comments/{id}",
     *     tags={"comments"},
     *     summary="Delete a comment",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the comment to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error: Comment could not be deleted"
     *     )
     * )
     */
    Flight::route('DELETE /@id', function($id) {
        $commentService = new CommentService();
        Flight::json($commentService->deleteComment($id));
    });
});


?>

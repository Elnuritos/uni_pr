<?php


/**
 * @OA\OpenApi(
 *   @OA\Info(
 *     title="API",
 *     version="1.0.0",
 *     description="API",
 *     @OA\Contact(
 *       email="el@el.com",
 *       name="El"
 *     )
 *   ),
 *   @OA\Server(
 *     url="http://localhost:9000",
 *     description="Local server"
 *   ),
 *   @OA\Tag(
 *     name="comments",
 *     description="Operations related to comments"
 *   ),
 *   @OA\Tag(
 *     name="auth",
 *     description="Authentication and authorization operations"
 *   ),
 *   @OA\Tag(
 *     name="articles",
 *     description="Operations related to articles"
 *   ),
 *   @OA\Tag(
 *     name="users",
 *     description="Operations related to users"
 *   )
 * )
 */
define('BASE_URL', 'http://localhost:9000/');
class OpenApi {
}
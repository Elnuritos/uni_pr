<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require_once __DIR__ . '/../services/UserService.class.php';


Flight::group('/users', function () {
    /**
     * @OA\Post(
     *   path="/users/add",
     *   tags={"users"},
     *   summary="Add a new user",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"name", "email", "password"},
     *       @OA\Property(property="name", type="string", example="John Doe"),
     *       @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *       @OA\Property(property="password", type="string", example="secret")
     *     )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="User created successfully"
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="User could not be created"
     *   )
     * )
     */

    Flight::route('POST /add', function () {
        $data = Flight::request()->data->getData();
        $userService = new UserService();
        if ($userService->createUser($data)) {
            $json_response = json_encode(['message' => 'User created successfully']);
            echo $json_response;
        } else {
            $json_response = json_encode(['message' => 'User could not be created']);
            http_response_code(400);
            echo $json_response;
        }
        exit;
    });
    /**
     * @OA\Put(
     *     path="/users/update/{user_id}",
     *     tags={"users"},
     *     summary="Update an existing user",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"name", "email"},
     *             @OA\Property(property="name", type="string", example="Jane Doe"),
     *             @OA\Property(property="email", type="string", example="jane.doe@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="User could not be updated"
     *     )
     * )
     */
    Flight::route('PUT /update/@user_id', function ($user_id) {
        $data = Flight::request()->data->getData();
        $userService = new UserService();
        if ($userService->updateUser($user_id, $data)) {
            Flight::json(['message' => 'User updated successfully']);
        } else {
            Flight::json(['message' => 'User could not be updated'], 400);
        }
    });
    /**
     * @OA\Delete(
     *     path="/users/delete/{user_id}",
     *     tags={"users"},
     *     summary="Delete a user",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="User could not be deleted"
     *     )
     * )
     */
    Flight::route('DELETE /delete/@user_id', function ($user_id) {
        $userService = new UserService();
        if ($userService->deleteUser($user_id)) {
            Flight::json(['message' => 'User deleted successfully']);
        } else {
            Flight::json(['message' => 'User could not be deleted'], 400);
        }
    });
    Flight::route('GET /getUserDetails', function () {
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
        $userService = new UserService();
        $user = $userService->getUserById($user_id);
        if ($user) {
            $json_response = json_encode($user);
            echo $json_response;
        } else {
            $json_response = json_encode(['message' => 'User not found']);
            http_response_code(404);
            echo $json_response;
        }
        exit;
    });
});

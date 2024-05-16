<?php

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
            Flight::json(['message' => 'User created successfully'], 201);
        } else {
            Flight::json(['message' => 'User could not be created'], 400);
        }
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
});

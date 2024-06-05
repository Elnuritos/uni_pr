<?php



require_once __DIR__ . '/../services/AuthService.class.php';


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::set('auth_service', new AuthService());

Flight::group('/auth', function () {

    /**
     * @OA\Post(
     *      path="/auth/login",
     *      tags={"auth"},
     *      summary="Login to system",
     *      @OA\Response(
     *           response=200,
     *           description="User data and JWT token"
     *      ),
     *      @OA\RequestBody(
     *          description="User credentials",
     *          @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", required=true, type="string", example="becir.isakovic@ibu.edu.ba"),
     *             @OA\Property(property="password", required=true, type="string", example="pass")
     *           )
     *      ),
     * )
     */
    Flight::route('POST /login', function () {

        $payload = Flight::request()->data->getData();
        $user = Flight::get('auth_service')->get_user_by_email($payload['email']);

        if (!$user || $payload['password'] != $user['password']) {
            Flight::json(['message' => 'Invalid username or password'], 401);
            exit;
        }

        $token_payload = [
            'user' => ['id' => $user['id']],
            'iat' => time(),
            'exp' => time() + 3600
        ];

        $token = JWT::encode($token_payload, JWT_SECRET, 'HS256');
        $response = ['id' => $user['id'], 'token' => $token];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    });
    /**
     * @OA\Post(
     *      path="/auth/logout",
     *      tags={"auth"},
     *      summary="Logout from system",
     *      security={
     *          {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Success response or exception"
     *      ),
     * )
     */
    Flight::route('POST /logout', function () {
        try {
            $token = Flight::request()->getHeader('Authentication');
            if ($token) {
                $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
                $response = [
                    'jwt_decoded' => $decoded_token,
                    'user' => $decoded_token->user
                ];
                $json_response = json_encode($response);
                echo $json_response;
                exit;
            }
        } catch (\Exception $e) {
            $json_response = json_encode(['message' => $e->getMessage()]);
            http_response_code(500);
            echo $json_response;
            exit;
        }
    });
});

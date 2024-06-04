<?php



require_once __DIR__ . '/../services/AuthService.class.php';



use Firebase\JWT\JWT;
use Firebase\JWT\Key;


Flight::route('/articles', function() {
    // Token is not needed for login or register page
    if (strpos(Flight::request()->url, '/auth/login') === 0) {
        return TRUE;
    } else {
        try {
            $token = Flight::request()->getHeader("Authorization");
            if(!$token)
                Flight::halt(401, "Unauthorized access. This will be reported to administrator!");

            $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
            Flight::set('user', $decoded_token->user);
            Flight::set('jwt_token', $token);
            return TRUE;
        } catch (\Exception $e) {
            Flight::halt(401, $e->getMessage());
        }
    }
});


?>

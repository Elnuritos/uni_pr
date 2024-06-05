<?php



require_once __DIR__ . '/../services/AuthService.class.php';



use Firebase\JWT\JWT;
use Firebase\JWT\Key;


Flight::route('/articles/*', function () {
    // Token is not needed for login or register page
    if (strpos(Flight::request()->url, '/auth/login') === 0) {
        return TRUE; // Continue if it's the login route
    }

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

    try {
        $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
        Flight::set('user', $decoded_token->user);
        return true; // Continue to the requested route
    } catch (\Exception $e) {
        Flight::json(['error' => $e->getMessage()], 401);
        return false;
    }
});

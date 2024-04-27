<?php

class ApiResponseHandler {

    public static function sendResponse($data, $code = 200) {
        header("Content-Type: application/json");
        http_response_code($code);
        echo json_encode($data);
        exit;
    }

    public static function sendError($message, $code) {
        self::sendResponse(['message' => $message], $code);
    }

}

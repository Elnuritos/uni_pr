<?php


$userController = new UserController();
$articleController = new ArticleController();

$routes = [
    '/signup' => [
        'method' => 'POST',
        'handler' => [$userController, 'signup']
    ],
    '/login' => [
        'method' => 'POST',
        'handler' => [$userController, 'login']
    ],
    '/getUserDetails'=>[
    'method' => 'GET',
    'handler' => [$userController, 'getUserDetails']
    ],

];
$routes['/articles'] = ['method' => 'GET', 'handler' => [$articleController, 'getAllArticles']];
$routes['/getArticle'] = ['method' => 'POST', 'handler' => [$articleController, 'getArticle']];
$routes['/updateArticle'] = ['method' => 'POST', 'handler' => [$articleController, 'updateArticle']];
$routes['/createArticle'] = ['method' => 'POST', 'handler' => [$articleController, 'createArticle']];
$routes['/getUserArticles'] = ['method' => 'GET', 'handler' => [$articleController, 'getUserArticles']];

return $routes;

<?php

require __DIR__.'/routes/MiddlewareRoutes.php';
require __DIR__.'/routes/UserRoutes.php';
require __DIR__.'/routes/AuthRoutes.php';
require __DIR__.'/routes/CommentsRoutes.php';
require __DIR__.'/routes/ArticlesRoutes.php';
Flight::route('/', function(){
    include __DIR__.'/redirect.php';
});
Flight::route('GET /docs', function(){
    include __DIR__.'/public/v1/docs/index.php';
});

Flight::start();

?>

<?php
require '../vendor/autoload.php';
//require __DIR__.'/routes/MiddlewareRoutes.php';
require __DIR__.'/routes/UserRoutes.php';
require __DIR__.'/routes/AuthRoutes.php';
require __DIR__.'/routes/CommentsRoutes.php';
require __DIR__.'/routes/ArticlesRoutes.php';


Flight::start();
?>

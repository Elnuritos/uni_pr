  <?php
  require 'vendor/autoload.php';

  require_once 'backend/config/Database.php';
  require_once 'backend/models/User.php';
  require_once 'backend/controllers/UserController.php';
  require_once 'backend/controllers/ArticleController.php';

  $routes = require 'backend/index.php';
?>

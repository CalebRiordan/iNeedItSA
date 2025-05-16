<?php

use Core\Router;
use Core\Session;

session_start();

// Autoloader
require __DIR__.'/../vendor/autoload.php';

// Services
require base_path('app/core/services.php');

// Router
$router = new Router();
require base_path("routes.php");
$uri = parse_url($_SERVER["REQUEST_URI"])['path'];

$method = $_SERVER['REQUEST_METHOD'];
$router->route($uri, $method);

Session::unflash();
?>

<!-- Global environment variables -->
<script>
  window.APP_CONFIG = {
    baseUrl: "<?= env("BASE_URL") ?>"
  };
</script>
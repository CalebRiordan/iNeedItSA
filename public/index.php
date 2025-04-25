<?php

use Core\Router;

require __DIR__.'/../vendor/autoload.php';

$env = require base_path('config/env.php');

$router = new Router();
require base_path("routes.php");

$uri = parse_url($_SERVER["REQUEST_URI"])['path'];
$method = $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);
?>
<script>
  window.APP_CONFIG = {
    baseUrl: "<?= $env["BASE_URL"] ?>"
  };
</script>
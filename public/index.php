<?php

use Core\Authenticator;
use Core\Router;
use Core\Session;

try {
  session_start();

  // Autoloader
  require __DIR__ . '/../vendor/autoload.php';

  // Services
  require base_path('app/core/services.php');

  // Automatic login
  (new Authenticator)->updateLoginState();

  // Router
  $router = new Router();
  require base_path("routes.php");
  $uri = $router->getUri();
  $router->route($uri, $_SERVER['REQUEST_METHOD']);

  Session::unflash();
} catch (Exception $ex) {
  abort(505);
} ?>

<!-- Global environment variables -->
<script>
  window.APP_CONFIG = {
    baseUrl: "<?= env("BASE_URL") ?>"
  };
</script>
<?php

use Core\Authenticator;
use Core\Router;
use Core\Session;

try {
  session_start();

  // Autoloader
  require __DIR__ . '/../vendor/autoload.php';

  // Set Error Logging
  error_reporting(E_ALL);
  ini_set('display_errors', '0');
  
  // Services
  require base_path('app/core/services.php');

  // Automatic login
  $auth = new Authenticator();
  $loggedIn = $auth->updateLoginState();
  if ($loggedIn) $auth->checkSellerState();

  // Router
  $router = new Router();
  require base_path("routes.php");
  $uri = $router->getUri();
  $method = $router->getMethod();
  $router->route($uri, $method);

} catch (Exception $ex) {
  dd($ex);
  // abort(500);
} ?>

<!-- Global environment variables -->
<script>
  // set config
  window.APP_CONFIG = {
    baseUrl: "<?= env("BASE_URL") ?>"
  };

  // set cart sync data
  if (<?= Session::has('sync_cart') ? 'true' : 'false' ?>){
    window.cartData = <?= json_encode(Session::get('cart')) ?>;
  } 
</script>

<?php Session::unflash(); ?>
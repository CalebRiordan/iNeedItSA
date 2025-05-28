<?php

use Core\Authenticator;
use Http\Forms\LoginForm;

$email = $_POST['email'];
$password = $_POST['password'];
$persistentLogin = $_POST['persist-login'] ?? false;

// Server-side form validation
$form = LoginForm::validate([
    'email' => $email,
    'password' => $password,
]);

// User Authentication
$auth = new Authenticator();
$signedIn = $auth->attempt($email, $password);
if (!$signedIn) {
    $form->error(
        'password',
        'No matching account found for that email address and password.'
    )->throw();
}

if ($persistentLogin) {
    $auth->setPersistentLoginCookie($email);
}

$cart = $_SESSION['cart'] ?? [];
$redirect = $_POST['previousPage'] ?? '/';

header("Content-Type: text/html");
?>
<script>
  const cart = <?= json_encode($cart) ?>;
  localStorage.setItem("cart", JSON.stringify(cart));
  window.location.href = "<?= $redirect ?>";
</script>
<?php exit ?>
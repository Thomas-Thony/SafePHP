<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    use SafePHP\Auth;
    use SafePHP\Form;

    $auth = new Auth( __DIR__ . "/../config", __DIR__ . "/../SafePHP-Logs/auth.log");
    
    if(isset($_POST["Se_connecter"])) {
        $name = $_POST["pseudo"];
        $password = $_POST["password"];
        $auth->login($_POST["Se_connecter"], $name, $password);
    } elseif(isset($_POST["S_inscrire"])) {
        $name = $_POST["name"];
        $mail= $_POST["email"];
        $password = $_POST["password"];
        $auth->register($name, $mail, $password);
        return;
    }
?>

<div class="main-container-auth-test">
    <div class="login-test-container">
        <h2>Login test</h2>
        <?php
            Form::createLoginForm("Se_connecter");
        ?>
    </div>

    <div class="register-test-container">
        <h2>Register test</h2>
            <?php
                Form::createRegisterForm("S_inscrire");
            ?>
    </div>
</div>

<?php

$testEssaisLogin = Auth::addIpTryLogin("127.0.0.1", 5, 15);

echo Auth::displayLoginAttempts();
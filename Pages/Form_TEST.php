<?php
require_once __DIR__ . '/../vendor/autoload.php';
use SafePHP\Form;
use SafePHP\Sanitize;
use SafePHP\CSRF;
use SafePHP\Auth;
$auth = new Auth(__DIR__ . "/../config", __DIR__ . "/../SafePHP-Logs/auth.log");
if(isset($_POST["Se_connecter"]) && $_POST["Se_connecter"] != null) {
    $auth->login($_POST["Se_connecter"], $_POST["name_input"], $_POST["password_input"]);
}
?>
<!--Formulaire de création de formulaire-->
<div class="creation-formulaire-container">
    <?php
        CSRF::createCSRF();
    ?>
    <h2>Créer un formulaire</h2>
    <form action="" method="POST">

        <div class="input-item-container">
            <label for="checkboxInput">Nombre de checkbox</label>
            <input type="number" name="number_checkbox">
            <br>
        </div>

        <div class="input-item-container">
            <label for="color_picker">Nombre de Color Picker</label>
            <input type="number" name="number_colorpicker">
            <br>
        </div>

        <div class="input-item-container">
            <label for="datetime">Nombre de date</label>
            <input type="number" name="number_date">
            <br>
        </div>

        <div class="input-item-container">
            <label for="datetimelocal">Nombre de DateTimeLocal</label>
            <input type="number" name="number_datetimelocal">
            <br>
        </div>

        <div class="input-item-container">
            <label for="checkboxInput">Nombre de email</label>
            <input type="number" name="number_mail">
            <br>
        </div>

        <div class="input-item-container">
            <label for="color_picker">Nombre de fichier</label>
            <input type="number" name="number_file">
            <br>
        </div>

        <div class="input-item-container">
            <label for="datetime">Nombre de image</label>
            <input type="number" name="number_image">
            <br>
        </div>

        <div class="input-item-container">
            <label for="datetimelocal">Nombre de mois</label>
            <input type="number" name="number_month">
            <br>
        </div>

        <div class="input-item-container">
            <label for="checkboxInput">Nombre de nombre</label>
            <input type="number" name="number_number">
            <br>
        </div>

        <div class="input-item-container">
            <label for="color_picker">Nombre de mot de passe</label>
            <input type="number" name="number_password">
            <br>
        </div>

        <div class="input-item-container">
            <label for="datetime">Nombre de radio</label>
            <input type="number" name="number_radio">
            <br>
        </div>

        <div class="input-item-container">
            <label for="datetimelocal">Nombre de Range</label>
            <input type="number" name="number_range">
            <br>
        </div>

        <div class="input-item-container">
            <label for="checkboxInput">Nombre de recherche</label>
            <input type="number" name="number_search">
            <br>
        </div>

        <div class="input-item-container">
            <label for="color_picker">Nombre de Téléphone</label>
            <input type="number" name="number_tel">
            <br>
        </div>

        <div class="input-item-container">
            <label for="datetime">Nombre de Texte</label>
            <input type="number" name="number_text">
            <br>
        </div>

        <div class="input-item-container">
            <label for="datetimelocal">Nombre de Time</label>
            <input type="number" name="number_time">
            <br>
        </div>

        <div class="input-item-container">
            <label for="datetimelocal">Nombre de Url</label>
            <input type="number" name="number_url">
            <br>
        </div>

        <div class="input-item-container">
            <label for="datetimelocal">Nombre de Week</label>
            <input type="number" name="number_week">
            <br>
        </div>

        <center>
            <button type="submit" name="creerForm" class="button-creer-formulaire">
                Créer le formulaire
            </button>
        </center>

    </form>
</div>

<!--Résultat-->
<div class="formulaire-creer-container">
    <?php
    if (isset($_POST["creerForm"])) {
        $InputSanitize = [
            'checkbox_sanitize' => Sanitize::sanitize($_POST["number_checkbox"], "int"),
            'color_sanitize' => Sanitize::sanitize($_POST["number_colorpicker"], "int"),
            'datetime_sanitize' => Sanitize::sanitize($_POST["number_date"], "int"),
            'datetimelocal_sanitize' => Sanitize::sanitize($_POST["number_datetimelocal"], "int"),
            'mail_sanitize' => Sanitize::sanitize($_POST["number_mail"], "int"),
            'file_sanitize' => Sanitize::sanitize($_POST["number_file"], "int"),
            'image_sanitize' => Sanitize::sanitize($_POST["number_image"], "int"),
            'month_sanitize' => Sanitize::sanitize($_POST["number_month"], "int"),
            'number_sanitize' => Sanitize::sanitize($_POST["number_number"], "int"),
            'password_sanitize' => Sanitize::sanitize($_POST["number_password"], "int"),
            'radio_sanitize' => Sanitize::sanitize($_POST["number_radio"], "int"),
            'range_sanitize' => Sanitize::sanitize($_POST["number_range"], "int"),
            'search_sanitize' => Sanitize::sanitize($_POST["number_search"], "int"),
            'tel_sanitize' => Sanitize::sanitize($_POST["number_tel"], "int"),
            'text_sanitize' => Sanitize::sanitize($_POST["number_text"], "int"),
            'time_sanitize' => Sanitize::sanitize($_POST["number_time"], "int"),
            'url_sanitize' => Sanitize::sanitize($_POST["number_url"], "int"),
            'week_sanitize' => Sanitize::sanitize($_POST["number_week"], "int"),

        ];
        Form::createForm(
            $InputSanitize["checkbox_sanitize"],
            $InputSanitize['color_sanitize'],
            $InputSanitize['datetime_sanitize'],
            $InputSanitize['datetimelocal_sanitize'],
            $InputSanitize['mail_sanitize'],
            $InputSanitize['file_sanitize'],
            $InputSanitize['image_sanitize'],
            $InputSanitize['month_sanitize'],
            $InputSanitize['number_sanitize'],
            $InputSanitize['password_sanitize'],
            $InputSanitize['radio_sanitize'],
            $InputSanitize['range_sanitize'],
            $InputSanitize['search_sanitize'],
            $InputSanitize['tel_sanitize'],
            $InputSanitize['text_sanitize'],
            $InputSanitize['time_sanitize'],
            $InputSanitize['url_sanitize'],
            $InputSanitize['week_sanitize'],
        );
    }

    ?>
</div>
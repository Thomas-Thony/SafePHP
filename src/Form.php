<?php
namespace SafePHP;
use SafePHP\CSRF;
use Exception;

/**
 * Generate form with safe statements (Sanitize, Verify, CSRF)
 */
class Form {
    private array $InputConfig;

    /**
     * Each time you get a form, whatever it is, verify the CSRF and the posted values
     * @return void function not finished yet
     */
    public static function getForm(){
        if (!CSRF::verifyCSRF()) {
            die("Jeton CSRF invalide !");
        }
    }

    /**
     * Renamme a file et move it on a temp folder
     * @param int each params are the numbet of each input to create, startinf at null (0)
     * @return void the form with the numnber of each HTML's inputs asked
     */
    public static function createForm(?int $NbCheckbox, ?int $NbColor, ?int $NbDate, ?int $NbDateTimeLocal, ?int $NbEmail, ?int $NbFile, ?int $NbImage, ?int $NbMonth, ?int $NbNumber , ?int $NbPassword, ?int $NbRadio, ?int $NbRange, ?int $NbSearch, ?int $NbTel, ?int $NbText, ?int $NbTime, ?int $NbUrl, ?int $NbWeek){

        CSRF::createCSRF(); //We add it on each form for a better security

        //Array list of every input possibles with their numbres in parameters
        $InputConfig = [
            'number_checkbox' => [
                'type' => 'checkbox',
                'count' => $NbCheckbox
            ],
            'number_colorpicker' => [
                'type' => 'color',
                'count' => $NbColor
            ],
            'number_date' => [
                'type' => 'date',
                'count' => $NbDate
            ],
            'number_datetimelocal' => [
                'type' => 'datetime-local',
                'count' => $NbDateTimeLocal
            ],
            'number_email' => [
                'type' => 'email',
                'count' => $NbEmail
            ],
            'number_file' => [
                'type' => 'file',
                'count' => $NbFile
            ],
            'number_image' => [
                'type' => 'image',
                'count' => $NbImage
            ],
            'number_month' => [
                'type' => 'month',
                'count' => $NbMonth
            ],
            'number_number' => [
                'type' => 'number',
                'count' => $NbNumber
            ],
            'number_password' => [
                'type' => 'password',
                'count' => $NbPassword
            ],
            'number_radio' => [
                'type' => 'radio',
                'count' => $NbRadio
            ],
            'number_range' => [
                'type' => 'range',
                'count' => $NbRange
            ],
            'number_search' => [
                'type' => 'search',
                'count' => $NbSearch
            ],
            'number_tel' => [
                'type' => 'tel',
                'count' => $NbTel
            ],
            'number_text' => [
                'type' => 'text',
                'count' => $NbText
            ],
            'number_time' => [
                'type' => 'time',
                'count' => $NbTime
            ],
            'number_url' => [
                'type' => 'url',
                'count' => $NbUrl
            ],
            'number_week' => [
                'type' => 'week',
                'count' => $NbWeek
            ],
        ];

        foreach ($_POST as $key => $value) {
            if (empty($value) || !isset($InputConfig[$key]))
                continue;
            try {
                $config = $InputConfig[$key];
                $baseName = str_replace('number_', '', $key) . '_template';
                for ($i = 0; $i < $config['count']; $i++) {
                    echo sprintf(
                        "<label for='%s_%d'> %s_%d </label>",
                        htmlspecialchars($config['type'], ENT_QUOTES, 'UTF-8'),
                        $i,
                        htmlspecialchars($baseName, ENT_QUOTES, 'UTF-8'),
                        $i
                    );
                    echo sprintf(
                        "<input type='%s' name='%s_%d'>",
                        htmlspecialchars($config['type'], ENT_QUOTES, 'UTF-8'),
                        htmlspecialchars($baseName, ENT_QUOTES, 'UTF-8'),
                        $i
                    );
                    echo "<br>";
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    /**
     * Create a classic login form
     * @param string $FormName name of the form to chech once submitted
     * @return void echo the whole form
     */
    public static function createLoginForm(string $FormName){
        echo "<form action='' method='POST'>";
        CSRF::createCSRF();
        echo " <input type='text' name='pseudo' placeholder='Nom' required>
                <input type='password' name='password' required>
                <button type='submit' name=" . $FormName . ">
                    Se connecter
                </button>";
        echo "</form>";
    }

    /**
     * Create a classic register form
     * @param string $FormName name of the form to chech once submitted
     * @return void echo the whole form
     */
    public static function createRegisterForm(string $FormName){
        echo "<form action='' method='POST'>";
        CSRF::createCSRF();
        echo " <input type='text' name='name_input' placeholder='Nom' required>
                <input type='mail' name='mail_input' placeholder='adresse@mail.com' required>
                <input type='password' name='password_input' required>
                <button type='submit' name=" . $FormName . ">
                    S'inscrire
                </button>";
        echo "</form>";
    }

    /**
     * Create a classic contact form
     * @param string $FormName name of the form to chech once submitted
     * @return void echo the whole form
     */
    public static function createContactForm($FormName) {
        echo "<form action='' method='POST'>";
        CSRF::createCSRF();
        echo " <input type='text' name='name_input' placeholder='Nom' required>
                <input type='mail' name='mail_input' placeholder='adresse@mail.com' required>
                <input type='password' name='password_input' required>
                <button type='submit' name=" . $FormName . ">
                    S'inscrire
                </button>";
        echo "</form>";
        }
}
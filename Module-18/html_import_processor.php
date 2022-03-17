<?php

/** Константы модуля */
define('PROJECT_CORE', true);
define('PROJECT_SERVER_PATH', '/php-developer-base/Module-18');
define('PROJECT_LOCAL_PATH', __DIR__);

/** Старт сессии */
session_start();

if (isset($_POST['URL_PARSE'])) {
    $_SESSION['URL_PARSE'] = $_POST['URL_PARSE'];
    header_remove();
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

if (isset($_SESSION['URL_PARSE'])) {
    try {
        if (! preg_match('/(http|https):\/\/\S{3,}\.\S{2,}/', $_SESSION['URL_PARSE'])) {
            throw new Exception('Некорректное поле URL');
        }

        /** Подключение к url из POST */
        $curl = curl_init($_SESSION['URL_PARSE']);

        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if (($response = curl_exec($curl)) === false) {
            throw new Exception('Ошибка запроса на указанный URL');
        }

        /** Подключение к url REST API */
        $request = json_encode(['raw_text' => $response]);

        $curlREST = curl_init('http://' . $_SERVER['HTTP_HOST'] . PROJECT_SERVER_PATH . '/HtmlProcessor.php');

        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curlREST, CURLOPT_POST, true);
        curl_setopt($curlREST, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curlREST, CURLOPT_RETURNTRANSFER, true);

        if (($responseREST = curl_exec($curlREST)) === false) {
            throw new Exception('Ошибка запроса по REST API');
        }
        if (($code = curl_getinfo($curlREST, CURLINFO_RESPONSE_CODE)) !== 200) {
            throw new Exception("Код ответа $code - " . json_decode($responseREST, true)['error']);
        }

        $message = json_decode($responseREST, true)['formatted_text'];
    } catch (Exception $error) {
        $errorMessage = $error->getMessage();
    }

    unset($_SESSION['URL_PARSE']);
}

/** Подключения хедера html, в конце скрипта подключается футер */
require_once PROJECT_LOCAL_PATH . '/app/templates/header.php';
?>

<?php if (isset($errorMessage)) :?>
    <div class="alert alert-danger padding-around">
        <b><?=$errorMessage?></b>
    </div>
<?php endif; ?>

<section class="flex-block">
    <?php if (isset($message)) :?>
        <div class="parse-block">
            <?=$message?>
        </div>
    <?php else :?>
        <form class="row g-3 needs-validation form-medium" method="POST" novalidate>
            <div class="form-floating">
                <input type="text" name="URL_PARSE" class="form-control" id="floatingUrl" placeholder="http" pattern="(http|https)://\S{3,}\.\S{2,}" required>
                <label class="form-label" for="floatingUrl">Введите URL для парсинга</label>
            </div>
            <div class="col-12 end-position">
                <button class="button-blue button on-dark" type="submit">Submit form</button>
            </div>
        </form>
    <?php endif;?>
</section>

<?php require_once PROJECT_LOCAL_PATH . '/app/templates/footer.php';?>

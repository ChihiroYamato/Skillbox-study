<?php
/** подключение ядра */
require_once __DIR__ . '/app/core/init.php';

/** Используемые классы */
use App\Base\Exceptions\FormException;
use App\Base\Skillbox\Entities\TelegraphText;
use App\Base\Helpers\Classes\Validator;
use App\Base\Helpers\Classes\HtmlSupport;
use App\Base\Helpers\Classes\MailerSupport;

/**
 * Проверка получения на страницу POST данных
 * Обработка POST происходит в массиве сессии после редиректа для очистки http запроса
 * (сессия стартует в ядре)
 */
if (isset($_POST['REQUEST_FROM']) && $_POST['REQUEST_FROM'] === 'Telegraph') {
    $_SESSION['POST_DATA'] = $_POST;
    header_remove();
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

/** Обработка данных из формы */
if (isset($_SESSION['POST_DATA'])) {
    try {
        /**
         * в модуле данные валидируются на стороне клиента инсрументарием html
         * и на сервере статичными методами класса App\Base\Helpers\Classes\Validator
         */
        $request = Validator::validateForm($_SESSION['POST_DATA']);

        /** создание текста */
        $newText = new TelegraphText($request['AUTHOR']);
        $newText->editText($request['TEXT'], $request['TITLE'])->storeText();

        /** Если зпаолнено поле EMAIL - происходит его обработка и отправка письма с помощью класса App\Base\Helpers\Classes\MailerSupport */
        if (! empty($_SESSION['POST_DATA']['EMAIL'])) {
            $email = Validator::validateEmail($_SESSION['POST_DATA']['EMAIL']);
            $mailer = new MailerSupport();
            $mailer->sendSimpleMail($request['TITLE'], $request['TEXT'], $email);
        }

        /**
         * с использованием класса App\Base\Helpers\Classes\HtmlSupport создается div блок
         * с пользовательским сообщением, блок записывается в переменную для последующего вывода в теле html документа
         */
        $message = (new HtmlSupport())->returnDiv('Текст успешно добавлен', 'SUCCESS');
    } catch (FormException $error) {
        /**
         * в модуле используются пользовательские Exception от класса App\Base\Exceptions\ProjectException
         * с возможностью логирования ошибков в XML файл
         */
        $error->toLogsXML();
        $message = (new HtmlSupport())->returnDiv($error->getMessage(), 'ALERT');
    }
    unset($_SESSION['POST_DATA']);
}

/** Подключения хедера html, в конце скрипта подключается футер */
require_once PROJECT_LOCAL_PATH . '/app/templates/header.php';
?>

<?=$message ?? ''?>
<section class="flex-block">
    <form class="row g-3 needs-validation form-medium" method="POST" action="#" novalidate>
        <input type="hidden" name="REQUEST_FROM" value="Telegraph">
        <div class="form-floating">
            <input type="text" name="AUTHOR" class="form-control" id="floatingAuthor" placeholder="John" pattern="[\w]{3,}" required>
            <label class="form-label" for="floatingAuthor">Автор</label>
        </div>
        <div class="form-floating">
            <input type="text" name="TITLE" class="form-control" id="floatingTitle" placeholder="text" required>
            <label class="form-label" for="floatingTitle">Заголовок</label>
        </div>
        <div class="form-floating">
            <textarea class="form-control textarea-height-important" name="TEXT" placeholder="text" id="floatingTextarea" required></textarea>
            <label for="floatingTextarea">Текст</label>
        </div>
        <div class="form-floating">
            <input type="text" name="EMAIL" class="form-control" id="floatingInput" placeholder="name@example.com" pattern="^[\w-]{3,}@[\w]{3,}\.[\w]{2,}$">
            <label class="form-label" for="floatingInput">Email адрес (опционально)</label>
        </div>
        <div class="col-12 end-position">
            <button class="button-blue button on-dark" type="submit">Submit form</button>
        </div>
    </form>
</section>

<?php require_once PROJECT_LOCAL_PATH . '/app/templates/footer.php';?>

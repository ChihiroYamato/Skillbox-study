<?php

/** Константы модуля */
define('PROJECT_CORE', true);
define('PROJECT_SERVER_PATH', '/php-developer-base/Module-17');
define('PROJECT_LOCAL_PATH', __DIR__);
define('FORM_MAX_FILE_SIZE', 10 * 1024 * 1024);
define('SERVER_MAX_FILE_SIZE', 2 * 1024 * 1024);

/** Старт сессии */
session_start();

/** Обработка входящей формы */
if (isset($_FILES['FORM_PHOTO']) && $_FILES['FORM_PHOTO']['size'] > 0) {
    try {
        /** Проверка сессии на загрузку файлов ранее */
        if (isset($_SESSION['FILES_COUNT']) && $_SESSION['FILES_COUNT'] >= 1) {
            unset($_SESSION['FILES_COUNT']);
            throw new Exception('Превышен допустимый лимит на загрузку файлов');
        }

        /** Проверка кода загрузки файла */
        if ($_FILES['FORM_PHOTO']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Ошибка загрузки файла на сервер. Код ошибки: ' . $_FILES['FORM_PHOTO']['error']);
        }

        /** Проверка расширения файла */
        if (! preg_match('/\.(png|jpg)$/', $_FILES['FORM_PHOTO']['name'])) {
            throw new Exception('Некорректное расширение файла. Допустимы расширения: JPG и PNG');
        }

        /** Проверка размера файла */
        if ($_FILES['FORM_PHOTO']['size'] > SERVER_MAX_FILE_SIZE) {
            throw new Exception('Превышен допустимый размер файла. Допустимое значение: ' . (SERVER_MAX_FILE_SIZE / (1024 * 1024)) . ' Мбайт');
        }

        /** Обработка и сохранение файла */
        $photoName = basename($_FILES['FORM_PHOTO']['name']);
        if (move_uploaded_file($_FILES['FORM_PHOTO']['tmp_name'], PROJECT_LOCAL_PATH . '/images/' . $photoName) === true) {
            if (isset($_SESSION['FILES_COUNT'])) {
                $_SESSION['FILES_COUNT']++;
            } else {
                $_SESSION['FILES_COUNT'] = 1;
            }

            header_remove();
            header('Location: http://' . $_SERVER['HTTP_HOST'] . PROJECT_SERVER_PATH . '/images/' . $photoName);
            exit;
        }
        /** Нахождение на этой строке свидетельствует провалу сохранения файла - выбрасывание исключения */
        throw new Exception('Ошибка сохранения файла');
    } catch (Exception $error) {
        /** Сохранение сообщения ошибки в сессию и редирект для очистки формы */
        $_SESSION['FORM_ERROR'] = $error->getMessage();
        header_remove();
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}

/** Подключения хедера html, в конце скрипта подключается футер */
require_once PROJECT_LOCAL_PATH . '/app/templates/header.php';
?>

<?php if (isset($_SESSION['FORM_ERROR'])) :?>
    <div class="alert alert-danger padding-around">
        <b><?=$_SESSION['FORM_ERROR']?></b>
    </div>
    <?php unset($_SESSION['FORM_ERROR']); ?>
<?php endif; ?>

<section class="flex-block">
    <form class="row g-3 needs-validation form-medium" method="POST" action="#" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="MAX_FILE_SIZE" value="<?=FORM_MAX_FILE_SIZE?>">
        <div class="mb-3">
            <label class="form-label">Загрузить фото</label>
            <input type="file" name="FORM_PHOTO" class="form-control" required>
        </div>
        <div class="col-12 end-position">
            <button class="button-blue button on-dark" type="submit">Submit form</button>
        </div>
    </form>
</section>

<?php require_once PROJECT_LOCAL_PATH . '/app/templates/footer.php';?>

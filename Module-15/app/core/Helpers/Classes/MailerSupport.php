<?php

namespace App\Base\Helpers\Classes;

use App\Base\Server\Settings;
use PHPMailer\PHPMailer\PHPMailer;
use App\Base\Helpers\Traits\SimpleExceptionHandler;
use PHPMailer\PHPMailer\Exception as MailerException;

/**
 * Класс помощник по работе с PHPMailer, поддерживает простую
 * отправку письма через метод sendSimpleMail(), или же продвинутую через методы-конструкторы
 * (все методы возвращают экземпляр текущего класса)
 *
 * @var PHPMailer $mail экземпляр объекта PHPMailer
 * @var array $settings массив настроек почты на сервере, берется из класса App\Base\Server\Settings
 * @var array $from массив информации об отправителе письма
 *
 * @method __construct :void string $fromAddress, string $fromName
 * @method sendSimpleMail :bool string $title, string $body, string $to
 * @method setServer :MailerSupport string $host, array $server
 * @method setRecipients :MailerSupport string $addressTo, string $nameTo
 * @method setContent :MailerSupport string $title, string $body, string $htmlBody
 * @method setAttachment :MailerSupport ...$Attachments
 * @method sendMail :bool
 */
final class MailerSupport
{
    use SimpleExceptionHandler;

    /** @var PHPMailer $mail экземпляр объекта PHPMailer */
    protected PHPMailer $mail;

    /** @var array $settings массив настроек почты на сервере, берется из класса App\Base\Server\Settings */
    protected array $settings;

    /** @var array $from массив информации об отправителе письма */
    protected array $from;

    /**
     * Инициализирует объект со свойствами: экземпляр класса PHPMailer, настройки почты на сервере
     * @param string $fromAddress [optional] email отправителя, если не задан, берется из настроек сервера
     * @param string $fromName [optional] имя отправителя, если не задано, берется из настроек сервера
     */
    public function __construct(string $fromAddress = '', string $fromName = '')
    {
        $this->mail = new PHPMailer(true);
        $this->settings = Settings::getMailSettings();

        if (! empty($fromAddress)) {
            $this->from['address'] = $fromAddress;

            if (! empty($fromName)) {
                $this->from['name'] = $fromName;
            }
        } else {
            $this->from['address'] = $this->settings['SEND']['FROM_ADDRESS'];
            $this->from['name'] = $this->settings['SEND']['FROM_NAME'];
        }
    }

    /**
     * Метод инициализирует отправку письма по упрощенной схеме
     * @param string $title заголовок письма
     * @param string $body текст письма
     * @param string $to [optional] адрес получателя, если не задано, письмо отправляется на почту администратора
     * @return bool возвращает результат отправки письма, true если не произошло ошибки
     */
    public function sendSimpleMail(string $title, string $body, string $to = '') : bool
    {
        $to = ($to) ?: $this->settings['SEND']['ADMIN_MAIL'];

        return $this->setServer()->setRecipients($to)->setContent($title, $body)->sendMail();
    }

    /**
     * Метод устанавливает настройки почтового сервера
     * @param string $host [optional] хост SMTP, если не задан, берется из настроек сервера
     * @param array $server [optional] если задан параметр $host - принимает в виде ассоциативного
     * массива следующие настройки сервера: ['port' - порт], ['auth' - SMTPAuth], ['secure' - SMTPSecure],
     * ['username' - логин почтового сервера], ['password' - пароль почтового сервера]
     * @return MailerSupport возвращает экземпляр текущего объекта
     * @throw PHPMailer\PHPMailer\Exception
     */
    public function setServer(string $host = '', array $server = []) : MailerSupport
    {
        try {
            $this->mail->isSMTP();

            if (empty($host)) {
                $this->mail->Host = $this->settings['SERVER']['HOST'];
                $this->mail->Port = 587;
                $this->mail->SMTPAuth = true;
                $this->mail->SMTPSecure = 'tls';
                $this->mail->Username = $this->settings['SERVER']['USER_NAME'];
                $this->mail->Password = $this->settings['SERVER']['PASSWORD'];
            } else {
                $this->mail->Host = $host;
                $this->mail->Port = (int) ($server['port'] ?? 587);
                $this->mail->SMTPAuth = (bool) ($server['auth'] ?? false);

                if ( $this->mail->SMTPAuth) {
                    $this->mail->SMTPSecure = (string) ($server['secure'] ?? 'tls');
                    $this->mail->Username = (string) ($server['username'] ?? '');
                    $this->mail->Password = (string) ($server['password'] ?? '');
                }
            }
        } catch (MailerException $error) {
            self::getFatalError($error, __CLASS__);
        }

        return $this;
    }

    /**
     * Метод устанавливает получателя письма
     * @param string $addressTo email получателя
     * @param string $nameTo [optional] имя получателя
     * @return MailerSupport возвращает экземпляр текущего объекта
     * @throw PHPMailer\PHPMailer\Exception
     */
    public function setRecipients(string $addressTo, string $nameTo = '') : MailerSupport
    {
        try {
            $this->mail->setFrom($this->from['address'], $this->from['name'] ?? '');
            $this->mail->addAddress($addressTo, $nameTo);
        } catch (MailerException $error) {
            self::getFatalError($error, __CLASS__);
        }

        return $this;
    }

    /**
     * Метод устанавливает контент письма
     * @param string $title заголовок письма
     * @param string $body текст письма
     * @param string $htmlBody [optional] текст письма в виде html, если задан - отправляется по умолчанию вместо $body
     * @return MailerSupport возвращает экземпляр текущего объекта
     * @throw PHPMailer\PHPMailer\Exception
     */
    public function setContent(string $title, string $body, string $htmlBody = '') : MailerSupport
    {
        try {
            $this->mail->Subject = $title;

            if (! empty($htmlBody)) {
                $this->mail->isHTML(true);
                $this->mail->Body = $htmlBody;
                $this->mail->AltBody = $body;
            } else {
                $this->mail->Body = $body;
            }
        } catch (MailerException $error) {
            self::getFatalError($error, __CLASS__);
        }

        return $this;
    }

    /**
     * Метод устанавливает вложения письма
     * @param array $Attachments данные по вложениям в виде списка, может принимать строки - путь к вложению
     * или массивы из двух элементов [путь к вложению, имя вложения]
     * @return MailerSupport возвращает экземпляр текущего объекта
     * @throw PHPMailer\PHPMailer\Exception
     */
    public function setAttachment(...$Attachments) : MailerSupport
    {
        try {
            if (! empty($Attachments)) {
                foreach ($Attachments as $Attachment) {
                    if (is_array($Attachment)) {
                        if (count($Attachment) > 1) {
                            list($path, $name) = $Attachment;
                        } else {
                            list($path) = $Attachment;
                        }
                    } else {
                        $path = $Attachment;
                    }

                    $this->mail->addAttachment($path, $name ?? '');
                }
            }
        } catch (MailerException $error) {
            self::getFatalError($error, __CLASS__);
        }

        return $this;
    }

    /**
     * Метод инициализирует отправку письма
     * @return bool озвращает результат отправки письма, true если не произошло ошибки
     * @throw PHPMailer\PHPMailer\Exception
     */
    public function sendMail() : bool
    {
        try {
            $this->mail->send();
        } catch (MailerException $error) {
            self::getFatalError($error, __CLASS__);
        }

        return true;
    }
}

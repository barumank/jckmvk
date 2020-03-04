<?php

namespace Backend\Library\Service\EmailService;

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Class EmailService
 *
 * @author  Artem Pasvlovskiy tema23p@gmail.com
 *
 * @package Backend\Library\Service
 */
class Manager
{
    /**
     * @var PHPMailer
     */
    private $phpMailer;
    /**
     * @var string
     */
    private $fromUserName;
    /**
     * @var string
     */
    private $fromEmail;

    /**
     * EmailService constructor.
     * @param \stdClass $settings
     */
    public function __construct( $settings)
    {
        $this->phpMailer = new PHPMailer();
        $this->phpMailer->isSMTP();
        //хост
        $this->phpMailer->Host = $settings->host;
        //указываем порт СМТП сервера
        $this->phpMailer->Port = $settings->port;
        //требует ли СМТП сервер авторизацию/идентификацию
        $this->phpMailer->SMTPAuth = $settings->SMTPAuth;
        // логин от вашей почты
        $this->phpMailer->Username = $settings->username;
        // пароль от почтового ящика
        $this->phpMailer->Password = $settings->password;
        //указываем способ шифромания сервера
        $this->phpMailer->SMTPSecure = $settings->SMTPSecure;
        //указываем кодировку для письма
        $this->phpMailer->CharSet = $settings->charSet;

        $this->fromEmail = $settings->fromEmail;
        $this->fromUserName = $settings->fromUserName;

    }

    /**
     * @param $htmlMsgBody
     * @param $messageTitle
     * @param $userEmail
     * @param $userName
     * @return bool
     */
    public function sendHtml($htmlMsgBody,$messageTitle, $userEmail, $userName)
    {


        try {
            $this->phpMailer->setFrom($this->fromEmail, $this->fromUserName);
            $this->phpMailer->addAddress($userEmail, $userName);
            $this->phpMailer->Subject = $messageTitle;
            $this->phpMailer->msgHTML($htmlMsgBody);
            $this->phpMailer->send();
        } catch ( \Exception $e ) {
            return false;
        }

        return true;
    }

}
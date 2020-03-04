<?php

namespace Backend\Library\Phalcon\Logger;

use Phalcon\Logger\Adapter;
use Phalcon\Logger\Formatter\Line as LineFormatter;

class UdpLogger extends Adapter
{


    private $loggerName;
    /**
     * @var string
     */
    protected $host;
    /**
     * @var int
     */
    protected $port;

    protected $socket;

    /**
     * RabbitLogger constructor.
     * @param $loggerName
     * @param $settings
     */
    public function __construct($loggerName, $settings)
    {
        $this->loggerName = $loggerName;
        $this->host = $settings['host'];
        $this->port = $settings['port'];

        if (!($this->socket = socket_create(AF_INET, SOCK_DGRAM, 0))) {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);
            die("Couldn't create socket: [$errorcode] $errormsg \n");
        }

    }

    /**
     * {@inheritdoc}
     *
     * @return \Phalcon\Logger\FormatterInterface
     */
    public function getFormatter()
    {
        if (!is_object($this->_formatter)) {
            $this->_formatter = new LineFormatter('%message%', 'Y-m-d H:i:s');
        }

        return $this->_formatter;
    }

    /**
     * Writes the log to the file itself
     *
     * @param string $message
     * @param integer $type
     * @param integer $time
     * @param array $context
     *
     * @return bool
     */
    public function logInternal($message, $type, $time, $context = [])
    {

        $messageJson = json_encode([
            'type' => $type,
            'name' => $this->loggerName,
            'message' => $message,
            'time' => $time,
        ], JSON_UNESCAPED_UNICODE);

        if (!socket_sendto($this->socket, $messageJson, strlen($messageJson), 0, $this->host, $this->port)) {
            /*$errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);*/

            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @return boolean
     */
    public function close()
    {
        if ($this->isTransaction()) {
            $this->commit();
        }
        socket_close($this->socket);
        return true;
    }

}
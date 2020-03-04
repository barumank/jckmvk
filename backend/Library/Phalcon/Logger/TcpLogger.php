<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 23.07.19
 * Time: 0:06
 */

namespace Backend\Library\Phalcon\Logger;


use Phalcon\Logger\Adapter;
use Phalcon\Logger\Formatter\Line as LineFormatter;

class TcpLogger extends Adapter
{

    /**
     * @var string
     */
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

    protected $label;

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
        $this->label = substr(md5(time() . '@' . rand(1, 10000) . '@' . uniqid()), 0, 8);
        $this->connect();
    }

    protected function connect()
    {
        if (($this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);
            die("Couldn't create socket: [$errorcode] $errormsg \n");
        }

        return socket_connect($this->socket, $this->host, $this->port);
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
                'time' => date('Y-m-d H:i:s', $time),
                'label' => $this->label
            ], JSON_UNESCAPED_UNICODE) . PHP_EOL;
        if (socket_write($this->socket, $messageJson, strlen($messageJson)) === false) {
            /*$errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);*/
            if ($this->connect()) {
                socket_write($this->socket, $messageJson, strlen($messageJson));
                return true;
            }
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    public function getLoggerName(): string
    {
        return $this->loggerName;
    }

    /**
     * @param string $loggerName
     * @return TcpLogger
     */
    public function setLoggerName(string $loggerName): TcpLogger
    {
        $this->loggerName = $loggerName;
        return $this;
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
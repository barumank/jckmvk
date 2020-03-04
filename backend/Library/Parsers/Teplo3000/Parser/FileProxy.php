<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 04.02.20
 * Time: 23:06
 */

namespace Backend\Library\Parsers\Teplo3000\Parser;


class FileProxy
{
    /**@var string */
    private $workDirectory;

    /**
     * FileProxy constructor.
     */
    public function __construct()
    {
        $this->workDirectory = __DIR__ . '/tmp';
    }

    public function set($fileName, $content)
    {
        $file = "{$this->workDirectory}/{$fileName}";
        file_put_contents($file, $content);
    }

    public function get($fileName): ?string
    {
        $file = "{$this->workDirectory}/{$fileName}";
        if (!file_exists($file)) {
            return null;
        }
        return file_get_contents($file);
    }

    public function clear()
    {
        foreach (glob("{$this->workDirectory}/*") as $file) {
            if (basename($file) !== '.gitignore') {
                unlink($file);
            }
        }
    }


}

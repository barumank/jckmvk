<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 05.02.20
 * Time: 22:59
 */

namespace Backend\Library\Parsers\SDK\Command;


use Backend\Library\Phalcon\Db\MysqlAdapter;

abstract class BaseCommand
{
    public abstract function save(): bool;

    public abstract function getDb(): MysqlAdapter;

    protected abstract function getSqlExistHashPattern(): string;

    public function undo()
    {
    }

    protected function getExistHashes(array $hashes): array
    {
        $out = [];
        if (empty($hashes)) {
            return $out;
        }
        $db = $this->getDb();
        $sqlPattern = $this->getSqlExistHashPattern();
        foreach ($this->getHashChunkGenerator($hashes) as $hashes){
            $sql = sprintf($sqlPattern, implode(',', $hashes));
            $result = $db->ping()->fetchAll($sql);
            foreach ($result as $item) {
                $out[$item['hash']] = $item;
            }
        }
        return $out;
    }

    private function getHashChunkGenerator(array $hashes):\Generator
    {
        $hashChunks = array_chunk($hashes,2000);
        $db = $this->getDb();
        foreach ($hashChunks as $key => $chunk){
            $chunk =  array_map(function ($item) use ($db) {
                return $db->escapeString($item);
            }, $chunk);
            yield $key => $chunk;
        }
    }

}

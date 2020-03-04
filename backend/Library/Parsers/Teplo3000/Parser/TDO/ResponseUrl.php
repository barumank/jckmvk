<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 04.02.20
 * Time: 22:34
 */

namespace Backend\Library\Parsers\Teplo3000\Parser\TDO;


class ResponseUrl
{
    /**@var string|null */
    private $url;
    /**@var string|null */
    private $text;

    /**
     * ResponseUrl constructor.
     * @param string|null $url
     * @param string|null $text
     */
    public function __construct(?string $url, ?string $text)
    {
        $this->url = $url;
        $this->text = $text;
    }


    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     * @return ResponseUrl
     */
    public function setUrl(?string $url): ResponseUrl
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string|null $text
     * @return ResponseUrl
     */
    public function setText(?string $text): ResponseUrl
    {
        $this->text = $text;
        return $this;
    }

    public function hash(): string
    {
        return md5($this->url);
    }


}

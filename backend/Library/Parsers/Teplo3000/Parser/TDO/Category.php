<?php
namespace Backend\Library\Parsers\Teplo3000\Parser\TDO;

class Category
{
    /**@var string|null*/
    private $name;
    /**@var string|null*/
    private $url;
    private $hash;

    /**
     * ProductCategory constructor.
     * @param string $name
     * @param string $url
     */
    public function __construct(string $name, string $url)
    {
        $this->name = $name;
        $this->url = $url;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return Category
     */
    public function setName(?string $name): Category
    {
        $this->name = $name;
        return $this;
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
     * @return Category
     */
    public function setUrl(?string $url): Category
    {
        $this->url = $url;
        $this->hash = null;
        return $this;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        if(!empty($this->hash)){
            return  $this->hash;
        }
        $this->hash = hash('sha256',$this->url);
        return $this->hash;
    }
}
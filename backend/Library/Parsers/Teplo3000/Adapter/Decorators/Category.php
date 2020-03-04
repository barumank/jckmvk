<?php


namespace Backend\Library\Parsers\Teplo3000\Adapter\Decorators;

use Backend\Library\Parsers\SDK\Interfaces\Category as CategoryInterface;
use Backend\Library\Parsers\Teplo3000\Parser\TDO\Category as TeploCategory;

class Category implements CategoryInterface
{
    /**@var int|null */
    private $id;
    /**@var int|null */
    private $userId;
    /**@var int|null */
    private $parentId;
    /**@var int|null */
    private $type;
    /**@var TeploCategory */
    private $categorySource;

    /**
     * ProductCategory constructor.
     * @param TeploCategory $categorySource
     */
    public function __construct(TeploCategory $categorySource)
    {
        $this->categorySource = $categorySource;
    }

    /**
     * @return TeploCategory
     */
    public function getCategorySource(): TeploCategory
    {
        return $this->categorySource;
    }

    /**
     * @param TeploCategory $categorySource
     * @return Category
     */
    public function setCategorySource(TeploCategory $categorySource): Category
    {
        $this->categorySource = $categorySource;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): CategoryInterface
    {
        $this->id = $id;
        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): CategoryInterface
    {
        $this->userId = $userId;
        return $this;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(?int $parentId): CategoryInterface
    {
       $this->parentId = $parentId;

       return $this;
    }


    public function getName(): ?string
    {
        return $this->categorySource->getName();
    }

    public function setName(string $name): CategoryInterface
    {
        $this->categorySource->setName($name);
        return $this;
    }

    public function getImage(): ?string
    {
        return 'null';
    }

    public function setImage(string $image): CategoryInterface
    {
        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): CategoryInterface
    {
        $this->type = $type;
        return $this;
    }

    public function hash():string
    {
        return hash('sha256', "{$this->categorySource->getUrl()}@{$this->userId}");
    }


}
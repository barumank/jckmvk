<?php


namespace Backend\Library\Parsers\SDK\Interfaces;


interface Category
{

    public function getId(): ?int;

    public function setId(int $id): Category;

    public function getUserId(): ?int;

    public function setUserId(int $userId): Category;

    public function getParentId(): ?int;

    public function setParentId(?int $parentId): Category;

    public function getName(): ?string;

    public function setName(string $name): Category;

    public function getImage(): ?string;

    public function setImage(string $image): Category;

    public function getType(): ?int;

    public function setType(int $type): Category;

    public function hash():string;

}
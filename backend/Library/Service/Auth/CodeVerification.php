<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 07.06.19
 * Time: 15:06
 */

namespace Backend\Library\Service\Auth;


class CodeVerification
{
    /**
     * @var string|null
     */
    private $code;
    /**
     * @var int|null
     */
    private $endTime;

    /**
     * @var bool
     */
    private $isConfirm =false;

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     * @return CodeVerification
     */
    public function setCode(?string $code): CodeVerification
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getEndTime(): ?int
    {
        return $this->endTime;
    }

    /**
     * @param int|null $endTime
     * @return CodeVerification
     */
    public function setEndTime(?int $endTime): CodeVerification
    {
        $this->endTime = $endTime;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getExpire(): ?int
    {
        if(empty($this->endTime)){
            return 0;
        }
        $expire = $this->endTime-time();
        if($expire<0){
            return 0;
        }
        return $expire;
    }

    /**
     * @return bool
     */
    public function isConfirm(): bool
    {
        return $this->isConfirm;
    }

    /**
     * @param bool $isConfirm
     * @return CodeVerification
     */
    public function setIsConfirm(bool $isConfirm): CodeVerification
    {
        $this->isConfirm = $isConfirm;
        return $this;
    }

}
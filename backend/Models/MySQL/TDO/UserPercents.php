<?php

namespace Backend\Models\MySQL\TDO;

class UserPercents
{
    /** @var double|null */
    protected $percent_user;
    /** @var double|null */
    protected $percent_partner_program;
    /** @var int|null*/
    protected $partner_id;

    /**
     * @return float|null
     */
    public function getPercentUser()
    {
        return $this->percent_user;
    }

    /**
     * @param float|null $percent_user
     * @return UserPercents
     */
    public function setPercentUser( $percent_user): UserPercents
    {
        $this->percent_user = $percent_user;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPercentPartnerProgram()
    {
        return $this->percent_partner_program;
    }

    /**
     * @param float|null $percent_partner_program
     * @return UserPercents
     */
    public function setPercentPartnerProgram( $percent_partner_program): UserPercents
    {
        $this->percent_partner_program = $percent_partner_program;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPartnerId()
    {
        return $this->partner_id;
    }

    /**
     * @param int|null $partner_id
     * @return UserPercents
     */
    public function setPartnerId($partner_id): UserPercents
    {
        $this->partner_id = $partner_id;
        return $this;
    }

}
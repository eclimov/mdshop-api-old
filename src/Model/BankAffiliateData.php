<?php

namespace App\Model;

use App\Entity\BankAffiliate;
use Symfony\Component\Validator\Constraints as Assert;

class BankAffiliateData {
    /**
     * @var int
     * @Assert\NotBlank(groups={"get"})
     */
    public int $id;

    /**
     * @var string
     * @Assert\NotBlank(groups={"create", "get"})
     */
    public string $affiliateNumber;

    /**
     * @param int $id
     * @return BankAffiliateData
     */
    public function setId(int $id): BankAffiliateData
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $affiliateNumber
     * @return BankAffiliateData
     */
    public function setAffiliateNumber(string $affiliateNumber): BankAffiliateData
    {
        $this->affiliateNumber = $affiliateNumber;

        return $this;
    }

    /**
     * @param BankAffiliate $bankAffiliate
     * @return BankAffiliateData
     */
    public function fill(BankAffiliate $bankAffiliate): BankAffiliateData
    {
        return $this
            ->setId($bankAffiliate->getId())
            ->setAffiliateNumber($bankAffiliate->getAffiliateNumber())
            ;
    }
}

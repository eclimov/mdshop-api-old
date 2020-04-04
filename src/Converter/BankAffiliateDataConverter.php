<?php

namespace App\Converter;

use App\Entity\BankAffiliate;
use App\Model\BankAffiliateData;

class BankAffiliateDataConverter {
    public function convert(BankAffiliate $bankAffiliate): BankAffiliateData
    {
        return (new BankAffiliateData())
            ->setId($bankAffiliate->getId())
            ->setAffiliateNumber($bankAffiliate->getAffiliateNumber())
            ;
    }
}

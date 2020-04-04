<?php

namespace App\Converter;

use App\Entity\BankAffiliate;
use App\Model\BankAffiliateData;

class BankAffiliateConverter {
    public function convert(BankAffiliateData $bankAffiliateData): BankAffiliate
    {
        return (new BankAffiliate())
            ->setAffiliateNumber($bankAffiliateData->affiliateNumber)
            ;
    }
}

<?php

namespace App\Converter;

use App\Entity\Bank;
use App\Model\BankData;

class BankConverter {
    public function convert(BankData $bankData): Bank
    {
        return (new Bank())
            ->setName($bankData->name)
            ;
    }
}

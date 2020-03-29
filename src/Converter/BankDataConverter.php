<?php

namespace App\Converter;

use App\Entity\Bank;
use App\Model\BankData;

class BankDataConverter {
    public function convert(Bank $bank): BankData
    {
        return (new BankData())
            ->setId($bank->getId())
            ->setName($bank->getName())
        ;
    }
}

<?php

namespace App\Converter;

use App\Entity\Company;
use App\Model\CompanyData;

class CompanyDataConverter {
    public function convert(Company $company): CompanyData
    {
        return (new CompanyData())
            ->setId($company->getId())
            ->setName($company->getName())
            ->setShortName($company->getShortName())
            ->setFiscalCode($company->getFiscalCode())
            ->setIban($company->getIban())
            ->setVat($company->getVat())
            ;
    }
}

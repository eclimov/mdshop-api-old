<?php

namespace App\Converter;

use App\Entity\Company;
use App\Model\CompanyData;

class CompanyConverter {
    public function convert(CompanyData $companyData): Company
    {
        return (new Company())
            ->setName($companyData->name)
            ->setShortName($companyData->shortName)
            ->setFiscalCode($companyData->fiscalCode)
            ->setIban($companyData->iban)
            ->setVat($companyData->vat)
            ;
    }
}

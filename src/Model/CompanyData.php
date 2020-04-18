<?php

namespace App\Model;

use App\Entity\Company;
use Symfony\Component\Validator\Constraints as Assert;

class CompanyData {
    /**
     * @var int
     * @Assert\NotBlank(groups={"get"})
     */
    public int $id;

    /**
     * @var string
     * @Assert\NotBlank(groups={"create", "get"})
     */
    public string $name;

    /**
     * @var string
     * @Assert\NotBlank(groups={"create", "get"})
     */
    public string $shortName;

    /**
     * @var string
     * @Assert\NotBlank(groups={"create", "get"})
     */
    public string $iban;

    /**
     * @var string
     * @Assert\NotBlank(groups={"create", "get"})
     */
    public string $fiscalCode;

    /**
     * @var string
     * @Assert\NotBlank(groups={"create", "get"})
     */
    public string $vat;

    /**
     * @param int $id
     * @return CompanyData
     */
    public function setId(int $id): CompanyData
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $name
     * @return CompanyData
     */
    public function setName(string $name): CompanyData
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $shortName
     * @return CompanyData
     */
    public function setShortName(string $shortName): CompanyData
    {
        $this->shortName = $shortName;

        return $this;
    }

    /**
     * @param string $iban
     * @return CompanyData
     */
    public function setIban(string $iban): CompanyData
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * @param string $fiscalCode
     * @return CompanyData
     */
    public function setFiscalCode(string $fiscalCode): CompanyData
    {
        $this->fiscalCode = $fiscalCode;

        return $this;
    }

    /**
     * @param string $vat
     * @return CompanyData
     */
    public function setVat(string $vat): CompanyData
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * @param Company $company
     * @return CompanyData
     */
    public function fill(Company $company): CompanyData
    {
        return $this
            ->setId($company->getId())
            ->setName($company->getName())
            ->setShortName($company->getShortName())
            ->setFiscalCode($company->getFiscalCode())
            ->setIban($company->getIban())
            ->setVat($company->getVat())
            ;
    }
}

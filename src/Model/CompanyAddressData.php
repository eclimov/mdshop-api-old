<?php

namespace App\Model;

use App\Entity\CompanyAddress;
use Symfony\Component\Validator\Constraints as Assert;

class CompanyAddressData {
    /**
     * @var int
     * @Assert\NotBlank(groups={"get"})
     */
    public int $id;

    /**
     * @var string
     * @Assert\NotBlank(groups={"create", "get"})
     */
    public string $address;

    /**
     * @var bool
     */
    public bool $isJuridic = false;

    /**
     * @param int $id
     * @return CompanyAddressData
     */
    public function setId(int $id): CompanyAddressData
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $address
     * @return CompanyAddressData
     */
    public function setAddress(string $address): CompanyAddressData
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @param bool $isJuridic
     * @return CompanyAddressData
     */
    public function setIsJuridic(bool $isJuridic): CompanyAddressData
    {
        $this->isJuridic = $isJuridic;

        return $this;
    }

    /**
     * @param CompanyAddress $companyAddress
     * @return CompanyAddressData
     */
    public function fill(CompanyAddress $companyAddress): CompanyAddressData
    {
        return $this
            ->setId($companyAddress->getId())
            ->setAddress($companyAddress->getAddress())
            ->setIsJuridic($companyAddress->isJuridic())
            ;
    }
}

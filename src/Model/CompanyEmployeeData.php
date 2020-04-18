<?php

namespace App\Model;

use App\Entity\CompanyEmployee;
use Symfony\Component\Validator\Constraints as Assert;

class CompanyEmployeeData {
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
     * @Assert\Choice(choices=App\Entity\CompanyEmployee::POSITIONS, groups={"create", "get"})
     */
    public string $position;

    /**
     * @param int $id
     * @return CompanyEmployeeData
     */
    public function setId(int $id): CompanyEmployeeData
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $name
     * @return CompanyEmployeeData
     */
    public function setName(string $name): CompanyEmployeeData
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $position
     * @return CompanyEmployeeData
     */
    public function setPosition(string $position): CompanyEmployeeData
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @param CompanyEmployee $companyEmployee
     * @return CompanyEmployeeData
     */
    public function fill(CompanyEmployee $companyEmployee): CompanyEmployeeData
    {
        return $this
            ->setId($companyEmployee->getId())
            ->setName($companyEmployee->getName())
            ->setPosition($companyEmployee->getPosition())
            ;
    }
}

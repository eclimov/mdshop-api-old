<?php

namespace App\Model;

use App\Entity\Bank;
use Symfony\Component\Validator\Constraints as Assert;

class BankData {
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
     * @param int $id
     * @return BankData
     */
    public function setId(int $id): BankData
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $name
     * @return BankData
     */
    public function setName(string $name): BankData
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param Bank $bank
     * @return BankData
     */
    public function fill(Bank $bank): BankData
    {
        return $this
            ->setId($bank->getId())
            ->setName($bank->getName())
            ;
    }
}

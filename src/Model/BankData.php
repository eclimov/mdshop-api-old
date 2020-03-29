<?php

namespace App\Model;

use Symfony\Component\Serializer\Annotation\Groups;

class BankData {
    /**
     * @var int
     * @Groups({"get"})
     */
    public int $id;

    /**
     * @var string
     * @Groups({"create", "get"})
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
}

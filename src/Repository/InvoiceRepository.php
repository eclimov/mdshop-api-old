<?php

namespace App\Repository;

use App\Entity\Invoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class InvoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoice::class);
    }

    /**
     * @return Invoice[]
     */
    public function findAllOrderDesc(): array
    {
        return $this->createQueryBuilder('i')
            ->addOrderBy('i.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
}

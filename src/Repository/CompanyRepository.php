<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    /**
     * @return Company[]
     */
    public function findVisibleOrderByName(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.hidden = :hidden')
            ->setParameters([
                'hidden' => false,
            ])
            ->orderBy('c.name')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Company[]
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.createdAt')
            ->getQuery()
            ->getResult();
    }
}

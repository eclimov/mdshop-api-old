<?php

namespace App\Repository;

use App\Entity\BankAffiliate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

class BankAffiliateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BankAffiliate::class);
    }

    /**
     * @param $bankId
     * @param $id
     * @return BankAffiliate|null
     * @throws NonUniqueResultException
     */
    public function findOneByBankIdAndId($bankId, $id): ?BankAffiliate
    {
        return $this->createQueryBuilder('ba')
            ->innerJoin('ba.bank', 'b')
            ->andWhere('b.id = :bankId')
            ->andWhere('ba.id = :id')
            ->setParameters([
                'bankId' => $bankId,
                'id' => $id,
            ])
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}

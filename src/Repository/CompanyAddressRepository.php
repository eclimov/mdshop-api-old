<?php

namespace App\Repository;

use App\Entity\BankAffiliate;
use App\Entity\Company;
use App\Entity\CompanyAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

class CompanyAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompanyAddress::class);
    }

    /**
     * @param Company $company
     * @return CompanyAddress[]
     */
    public function findJuridicByCompany(Company $company): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.company = :company')
            ->andWhere('a.juridic = :juridic')
            ->setParameters([
                'company' => $company,
                'juridic' => true,
            ])
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $companyId
     * @param $id
     * @return BankAffiliate|null
     * @throws NonUniqueResultException
     */
    public function findOneByCompanyIdAndId($companyId, $id): ?CompanyAddress
    {
        return $this->createQueryBuilder('ca')
            ->innerJoin('ca.company', 'c')
            ->andWhere('c.id = :companyId')
            ->andWhere('ca.id = :id')
            ->setParameters([
                'companyId' => $companyId,
                'id' => $id,
            ])
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}

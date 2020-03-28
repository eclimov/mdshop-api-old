<?php

namespace App\Repository;

use App\Entity\Company;
use App\Entity\CompanyAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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
}

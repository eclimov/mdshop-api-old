<?php

namespace App\Repository;

use App\Entity\BankAffiliate;
use App\Entity\Company;
use App\Entity\CompanyEmployee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;

class CompanyEmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompanyEmployee::class);
    }

    /**
     * @param Company $company
     * @return QueryBuilder
     */
    public function getCompanyEmployeesOrderDirectorLastQb(Company $company): QueryBuilder
    {
        return $this->createQueryBuilder('ce')
            ->addSelect('ce')
            ->addSelect('CASE WHEN ce.position = :director_position THEN 1 ELSE 0 END AS HIDDEN ord')
            ->where('ce.company = :company')
            ->setParameters([
                'company' => $company,
                'director_position' => 'Director',
            ])
            ->orderBy('ord', 'ASC')
        ;
    }

    /**
     * @param $companyId
     * @param $id
     * @return BankAffiliate|null
     * @throws NonUniqueResultException
     */
    public function findOneByCompanyIdAndId($companyId, $id): ?CompanyEmployee
    {
        return $this->createQueryBuilder('ce')
            ->innerJoin('ce.company', 'c')
            ->andWhere('c.id = :companyId')
            ->andWhere('ce.id = :id')
            ->setParameters([
                'companyId' => $companyId,
                'id' => $id,
            ])
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}

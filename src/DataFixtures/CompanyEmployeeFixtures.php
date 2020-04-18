<?php

namespace App\DataFixtures;

use App\Entity\CompanyEmployee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CompanyEmployeeFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('en_US');
        $employeePositions = CompanyEmployee::POSITIONS;
        for ($i = 0; $i < 20; $i++) {
            foreach ($employeePositions as $j => $employeePosition) {
                $companyEmployee = new CompanyEmployee();
                $companyEmployee->setName($faker->name);
                $companyEmployee->setPosition($employeePosition);
                $companyEmployee->setCompany($this->getReference('company_' . $i));
                $manager->persist($companyEmployee);
                $this->addReference('companyEmployee_' . $i . $j, $companyEmployee);
            }
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            CompanyFixtures::class,
        ];
    }
}

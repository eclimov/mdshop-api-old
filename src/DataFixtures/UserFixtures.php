<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    // 'bin/console security:encode-password' to get encoded hardcode

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $roles = [
            User::ROLE_ADMIN,
            User::ROLE_SUPER_USER,
            User::ROLE_USER,
        ];

        for ($i = 0; $i < 3; $i++) {
            $userNumber = $i + 1;

            $user = new User('t' . $userNumber . '@mail.com');
            $user->setRoles([$roles[$i]]);
            $user->setPassword($this->passwordEncoder->encodePassword($user, '1' . $userNumber));
            $manager->persist($user);
            $this->addReference('user_' . $userNumber, $user);
        }
        $manager->flush();
    }
}

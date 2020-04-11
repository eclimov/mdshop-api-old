<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    // 'bin/console security:encode-password' to get encoded hardcode

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $user1 = new User('t1@mail.com');
        $user1->setPassword('$2y$13$XhNHSB38287Bg37xnNHT0ubkEyFDpo2Eu/a1dN7EgG2X.ZOoWVn.a');  // 11
        $user1->setRoles([User::ROLE_ADMIN]);
        $manager->persist($user1);
        $this->addReference('user_1', $user1);

        $user2 = new User('t2@mail.com');
        $user2->setRoles([User::ROLE_USER]);
        $user2->setPassword('$2y$13$NV5hu.XBefam/VsV2doD8uZdZC2KlLslhX9Jbtunu4E9pRIHltDJO'); // 12
        $manager->persist($user2);
        $this->addReference('user_2', $user2);

        $manager->flush();
    }
}

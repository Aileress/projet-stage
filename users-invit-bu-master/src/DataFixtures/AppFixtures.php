<?php

namespace App\DataFixtures;

use App\Entity\UserBu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTime;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 1000; $i++) {
            $user = new UserBu();
            $user->setFirstName('user '.$i);
            $user->setLastName('qsdf');
            $user->setBirthday(new DateTime('10/26/2003'));
            $user->setBirthPlace('fghg');
            $user->setEmail('fghtyh');
            $user->setCreationDate(new DateTime('now'));
            $user->setListeService(['toto1', 'toto2']);
            $manager->persist($user);
        }

        $manager->flush();
    }
}

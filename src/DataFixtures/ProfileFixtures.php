<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Profile;
use App\Entity\User;

class ProfileFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $profile = new Profile();
        $profile
            ->setFirstname('Hans')
            ->setLastname('Keller')
            ->setCompany('K & E')
            ->setPhone('+49 8221215095')
            ->setWebsite('https://www.k-e.de/')
            ->setLanguage('de');
        $manager->persist($profile);

        $user = new User();
        $user
            ->setProfile($profile)
            ->setEmail('ke.configurator@gmail.com')
            ->setPassword('d5NzZFE7s9ZsVzJk2ETZFAWyi592GUHKWhit3iuy8hRTDIuUrDOXMiW3+odX7M8F2SKS5Z9ED8i6+rh/E4fJyg==')
            ->setEnabled(true)
            ->addRole('ROLE_ADMIN');
        $manager->persist($user);

        $manager->flush();
    }
}

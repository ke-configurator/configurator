<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\CalculationGroup;

class CalculationGroupFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $calculationGroup  = new CalculationGroup();
        $calculationGroup
            ->setTitle('Kabel')
            ->setDescription('Kabel Dimensionierungen')
            ->setPosition(1);
        $manager->persist($calculationGroup);

        $calculationGroup  = new CalculationGroup();
        $calculationGroup
            ->setTitle('Pumpen')
            ->setDescription('Pumpen Dimensionierungen')
            ->setPosition(2);
        $manager->persist($calculationGroup);

        $manager->flush();
    }
}

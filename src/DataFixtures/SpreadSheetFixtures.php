<?php

namespace App\DataFixtures;

use App\Entity\SpreadSheet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SpreadSheetFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $spreadSheet = new SpreadSheet();
        $spreadSheet
            ->setName('Spannungsfall')
            ->setUid('19WfVnc16d4CZTiAIv75UbzO5pLeZBmLT')
            ->setIsActive(true);
        $manager->persist($spreadSheet);

        $manager->flush();
    }
}

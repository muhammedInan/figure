<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Figure;

class FigureFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 10; $i++){
            $figure = new Figure();
            $figure->setTitle("Titre de l'article n°$i")
                   ->setContent("<p>Contenu de l'article n°$i</p>")
                   ->setImage("http:://placehold.it/350*150")
                   ->setCreateAt(new \DateTime());
            $manager->persist($figure);
    }

        $manager->flush();
    }
}

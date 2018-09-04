<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Figure;
use App\Entity\Comment;

class FigureFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = \Faker\Factory::create('fr_FR');

        for($i = 1; $i <= 5; $i++){
            $figure = new Figure();
            $content = '<p>'.join($faker->paragraphs(5),'</p><p>').
            '</p>';
            $figure->setTitle($faker->sentence())
                   ->setContent($content)
                   ->setImage($faker->imageUrl())
                   ->setCreateAt($faker->DateTimeBetween('-6 months'));
            $manager->persist($figure);

            //crée commentaire
         for($j =  1; $j <= mt_rand(4,6); $j++) {
           $comment = new Comment();
             $content = '<p>'.join($faker->paragraphs(2),'</p><p>').
                 '</p>';
             $days = (new \DateTime())->diff($figure->getCreateAt
             ())->days;

             $comment->setAuthor($faker->name)
                     ->setContent($content)
                     ->setCreatedAt($faker->dateTimeBetween('-'.
                     $days . 'days'))
                     ->setFigure($figure);

             $manager->persist($comment);



         }
    }

        $manager->flush();
    }
}

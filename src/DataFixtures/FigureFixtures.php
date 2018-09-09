<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Figure;
use App\Entity\Comment;

class FigureFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = \Faker\Factory::create('fr_FR');

        for($i = 1; $i <= 3; $i++){
            $category = new Category();
            $category->setTitle($faker->sentence())
                  ->setDescription($faker->paragraph());

         $manager->persist($category);
        }

        for($j = 1; $j <= 5; $j++){
            $figure = new Figure();
            $content = '<p>'.join($faker->paragraphs(5),'</p><p>').
            '</p>';
            $figure->setTitle($faker->sentence())
                   ->setContent($content)
                   ->setImage($faker->imageUrl())
                   ->setCreateAt($faker->DateTimeBetween('-6 months'))
                   ->setCategory($category);


            $manager->persist($figure);

            //crée commentaire
         for($k =  1; $k <= mt_rand(4,6); $k++) {
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

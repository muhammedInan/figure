<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Figure;
use App\Entity\Comment;
use App\Entity\Video;
use App\Entity\Image;
use App\Entity\User;

class FigureFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $videos = [
         'https://www.youtube.com/watch?v=myZKTpqbAyg',
         'https://www.youtube.com/watch?v=myZKTpqbAyg',
         'https://www.youtube.com/watch?v=myZKTpqbAyg',
         'https://www.youtube.com/watch?v=myZKTpqbAyg',
         'https://www.youtube.com/watch?v=myZKTpqbAyg',
        ];

        $images = [
            '88ca9205e34a97a59d63f6bd90f83af9795f296a.jpeg',
            '88ca9205e34a97a59d63f6bd90f83af9795f296a.jpeg',
            '88ca9205e34a97a59d63f6bd90f83af9795f296a.jpeg',
            '88ca9205e34a97a59d63f6bd90f83af9795f296a.jpeg',
            '88ca9205e34a97a59d63f6bd90f83af9795f296a.jpeg',
        ];


        $faker = \Faker\Factory::create('fr_FR');

        $user = new User();

            $user->setEmail('muhammed-inan@outlook.com' );
            $user->setFirstname('muhammed');
            $user->setUsername('toto');
            $user->setPassword('totototo');

            $user->setLastname('inan');
        $image = new Image();

        $image->setPath('88ca9205e34a97a59d63f6bd90f83af9795f296a.jpeg');
        $user->setImage($image);

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
                   ->setCreateAt($faker->DateTimeBetween('-6 months'))
                   ->setCategory($category);
            $video = new Video();
            $video->setUrl($videos[$j-1]);
            $figure->setVideo($video);

            $image = new Image();

            $image->setPath($images[$j-1]);
            $figure->addImage($image);


            $manager->persist($figure);

            //crée commentaire
         for($k =  1; $k <= mt_rand(4,6); $k++) {
           $comment = new Comment();
             $content = '<p>'.join($faker->paragraphs(2),'</p><p>').
                 '</p>';
             $days = (new \DateTime())->diff($figure->getCreateAt
             ())->days;

             $comment->setAuthor($user)
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

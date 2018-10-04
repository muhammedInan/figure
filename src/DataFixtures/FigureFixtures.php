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
            'https://www.youtube.com/watch?v=n0F6hSpxaFc',
            'https://www.youtube.com/watch?v=crDzvmi91XQ',
            'https://www.youtube.com/watch?v=obHWnBYl3Eo',
            'https://www.youtube.com/watch?v=y4Yp3-llWDs',
            'https://www.youtube.com/watch?v=V9xuy-rVj9w',
            'https://www.youtube.com/watch?v=8CtWgw9xYRE',
            'https://www.youtube.com/watch?v=KoHzXi7Usl8',
            'https://www.youtube.com/watch?v=ewAPUJMprgI',
            'https://www.youtube.com/watch?v=tbD14cpo1qk',

        ];

        $images = [
            '0f74346f7c57747a75a91b2a51bdc982a8e427de.jpeg',
            '88ca9205e34a97a59d63f6bd90f83af9795f296a.jpeg',
            '28e9b0b18c216212c974bd975334dfb2a66dcd82.jpeg',
            '460af889b3b86bdb340523f3f018e7b891116e3d.jpeg',
            '628e8dd4019e901fc053b6dc4d90d8fcdd6036d8.jpeg',
            'bbf5e900cb1fd17fedbb4a5b2d8341a0bb6e011f.jpeg',
            'cc8dc569bdcf6df14dc5e1f469ee3fa9d34d2b08.jpeg',
            '7d038557847baec98c710a29aab7d5fd57887c3e.jpeg',
            'bc0c32ebf3a1975546ac6b3ce49b2b687057f6e2.jpeg',
        ];


        $faker = \Faker\Factory::create('fr_FR');

        $user = new User();

        $user->setEmail('muhammed-inan@outlook.com');
        $user->setFirstname('muhammed');
        $user->setUsername('toto');
        $user->setPassword('totototo');
        $user->setLastname('inan');
        $image = new Image();

        $image->setPath('88ca9205e34a97a59d63f6bd90f83af9795f296a.jpeg');
        $user->setImage($image);

        for ($i = 1; $i <= 3; $i++) {
            $category = new Category();
            $category->setTitle($faker->sentence())
                ->setDescription($faker->paragraph());

            $manager->persist($category);
        }

        for ($j = 1; $j <= 5; $j++) {
            $figure = new Figure();
            $content = '<p>' . join($faker->paragraphs(5), '</p><p>') .
                '</p>';
            $figure->setTitle($faker->sentence())
                ->setContent($content)
                ->setCreateAt($faker->DateTimeBetween('-6 months'))
                ->setCategory($category);
            $video = new Video();
            $video->setUrl($videos[$j - 1]);
            $figure->setVideo($video);

            $image = new Image();

            $image->setPath($images[$j - 1]);
            $figure->addImage($image);


            $manager->persist($figure);

            //crée commentaire
            for ($k = 1; $k <= mt_rand(4, 6); $k++) {
                $comment = new Comment();
                $content = '<p>' . join($faker->paragraphs(2), '</p><p>') .
                    '</p>';
                $days = (new \DateTime())->diff($figure->getCreateAt
                ())->days;

                $comment->setAuthor($user)
                    ->setContent($content)
                    ->setCreatedAt($faker->dateTimeBetween('-' .
                        $days . 'days'))
                    ->setFigure($figure);

                $manager->persist($comment);
            }
        }

        $manager->flush();
    }
}

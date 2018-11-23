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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FigureFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {

        $this->encoder = $encoder;
    }

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
            'image.jpg',
            'image2.jpg',
            'image3.jpg',
            'image4.jpg',
            'image5.jpg',
            'image10.jpg',
            'image7.jpg',
            'image8.jpg',
            'image9.jpg',
        ];

        $faker = \Faker\Factory::create('fr_FR');

        $user = (new User());
        $user->setEmail('muhammed-inan@outlook.com');
        $user->setFirstname('muhammed');
        $user->setUsername('toto');
        $user->setPassword('totototo');
        $user->setLastname('inan');
        $password = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);
        $manager->persist($user);
        $userConfirm = (new User());
        $userConfirm->setEmail('muhammed-inan@outlook.com');
        $userConfirm->setFirstname('muhammed');
        $userConfirm->setUsername('toto');
        $userConfirm->setPassword('totototo');
        $userConfirm->setLastname('inan');
        $userConfirm->setValidationToken('my_test_validation_token');
        $password = $this->encoder->encodePassword($userConfirm, $userConfirm->getPassword());
        $userConfirm->setPassword($password);
        $manager->persist($userConfirm);
        $userReinit = (new User());
        $userReinit->setEmail('muhammed-inan@outlook.com');
        $userReinit->setUsername('toto');
        $userReinit->setPassword('totototo');
        $userReinit->setLastname('inan');
        $userReinit->setResetToken('my_test_reset_token');

        $password = $this->encoder->encodePassword($userReinit, $userReinit->getPassword());
        $userReinit->setPassword($password);

        $manager->persist($userReinit);

        $image = new Image();

        $image->setPath('image2.jpg');
        $user->setImage($image);

    $categories = [
        'Les grabs',
        'Les rotations',
        'Les flips',
    ];
        foreach ($categories as $titleCategory) {
            $category = new Category();
            $category->setTitle($titleCategory);

            $manager->persist($category);
        }

        $figures = [
            [
                 'title' => 'mute',

                 'content' => 'saisie de la carre frontside de la planche entre les deux pieds avec la main avant',
            ],
            [
                'title' => 'sad',

                'content' => 'saisie de la carre backside de la planche, entre les deux pieds, avec la main avant ',
            ],
            [
                'title' => 'indy',

                'content' => 'saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière ',
            ],
            [
                'title' => '360',

                'content' => 'trois six pour un tour complet ',
            ],
            [
                'title' => '180',

                'content' => 'désigne un demi-tour, soit 180 degrés d\'angle ',
            ],
            [
                'title' => 'Hakon Flip',

                'content' => 'de manière beaucoup plus rare, et se confondent souvent avec certaines rotations horizontales désaxées',
            ],
            [
                'title' => 'truck driver',

                'content' => 'saisie du carre avant et carre arrière avec chaque main ',
            ],
            [
                'title' => 'seat belt',

                'content' => 'saisie du carre frontside à l\'arrière avec la main avant',
            ],
            [
                'title' => '720',

                'content' => 'sept deux pour deux tours complets',
            ],
            [
                'title' => 'Mac Twist',

                'content' => 'On distingue les front flips, rotations en avant, et les back flips, rotations en arrière ',
            ],
        ];

        foreach($figures as $dataFigure) {
            $figure = new Figure();
            $content = '<p>' . join($faker->paragraphs(5), '</p><p>') .
                '</p>';
            $figure->setTitle($dataFigure['title'])
                ->setContent($dataFigure['content'])
                ->setCreateAt($faker->DateTimeBetween('-6 months'))
                ->setCategory($category);

            for($i=0;$i <= $length = mt_rand(1,sizeof($images)-1);$i++) {
                $image = new Image();
                $image->setPath($images[mt_rand(0, sizeof($images)-1)]);
                $figure->addImage($image);

          }
            for($i=0;$i <= $length = mt_rand(1,sizeof($videos)-1);$i++) {
                $video = new Video();
                $video->setUrl($videos[mt_rand(0, sizeof($videos)-1)]);
                $video->extractIdentif();
                $figure->addVideo($video);

            }

            $figure->setUser($user);
            $manager->persist($figure);
        }

        for ($k = 1; $k <= mt_rand(4, 6); $k++) {
            $comment = new Comment();
            $content = '<p>' . join($faker->paragraphs(2), '</p><p>') .
                '</p>';
            $days = (new \DateTime())->diff($figure->getCreateAt())->days;
            $comment->setAuthor($user)
                ->setContent($content)
                ->setCreatedAt($faker->dateTimeBetween('-' . $days . 'days'))
                ->setFigure($figure);
            $manager->persist($comment);
        }

        $manager->flush();

    }
}


<?php
namespace App\Event\Listener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use App\Entity\Figure;
use App\Entity\Video;
use App\Entity\Image;



class VideoListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // only act on some "Product" entity
        if (!$entity instanceof Video) {
            return;
        }
        
        $entityManager = $args->getEntityManager();
         
            $entity->extractIdentif();
         }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // only act on some "Product" entity
        if (!$entity instanceof Video) {
            return;
        }
        die('update');
        $entityManager = $args->getEntityManager();

    }

    
    private function uploadIdentif($args, $entity)
    { 

        if (!$entity instanceof Video) {
             
           
            return;
        }
        die('ok');
       foreach($entity->getVideos() as $video) {
            $video->extractIdentif();}
        $entityManager = $args->getObjectManager();
        $entityManager->persist($entity);
        $entityManager->flush();
    // dump($video);die;
        
    
            

    }

}
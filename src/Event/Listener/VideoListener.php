<?php
namespace App\Event\Listener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use App\Entity\Figure;


class VideoListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadIdentif($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadIdentif($entity);
    }
    
//    public function preFlush(PreFlushEventArgs $args)
//    {
//        $entity = $args->getEntity();
//
//        $this->uploadIdentif($entity);
//    }
    
    private function uploadIdentif($entity)
    {
        if (!$entity instanceof Figure) {
            return;
        }
        $entity->getVideo()->extractIdentif();
    }

}
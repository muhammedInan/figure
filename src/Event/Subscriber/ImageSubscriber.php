<?php

namespace App\Event\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Event\Constants\ImageEvents;
use App\Event\ImageCollectionEvent;
use App\Event\ImageEvent;

class ImageSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            ImageEvents::PRE_UPLOAD => 'imagePreUpload',
            ImageEvents::POST_UPLOAD => 'imagePostUpload',
            ImageEvents::PRE_REMOVE => 'imagePreRemove',
            ImageEvents::POST_REMOVE => 'imagePostRemove',
        );
    }

    public function imagePreUpload(ImageCollectionEvent $event)
    {

        foreach($event->getImages() as $image){
            $image->preUpload();

        }
        return $event->getImages();
    }
    public function imagePostUpload(ImageCollectionEvent $event)
    {
        foreach($event->getImages() as $image){
            $image->upload();
        }
    }

    public function imagePreRemove(ImageCollectionEvent $event)
    {
        foreach($event->getImages() as $image){
            $image->storeFilenameForRemove();
        }
    }
    public function imagePostRemove(ImageCollectionEvent $event)
    {
        foreach($event->getImages() as $image){
            $image->removeUpload();
        }
    }
}






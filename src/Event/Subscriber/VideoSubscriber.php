<?php

namespace App\Event\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Event\Constants\VideoEvents;
use App\Event\VideoCollectionEvent;
use App\Repository\VideoRepository;
use App\Entity\Video;

class VideoSubscriber implements EventSubscriberInterface
{
    private $videoRepository;

    public function __construct(VideoRepository $videoRepository)
    {
        $this->videoRepository = $videoRepository;
    }

    public static function getSubscribedEvents()
    {
        return array(
            VideoEvents::PRE_EXTRACT_IDENTIF => 'onVideoEdit',

        );
    }

    public function onVideoEdit(VideoCollectionEvent $event)
    {
        $videos = $event->getVideos();

        foreach ($videos as $video) {
            $video->extractIdentif();

            $this->videoRepository->save($video);
        }
    }

}

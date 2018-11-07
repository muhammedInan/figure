<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;



class VideoCollectionEvent extends Event
{
    private $videos;

    public function __construct(array $videos)
    {
        $this->videos = $videos;
    }
    public function getVideos()
    {
        return $this->videos;
    }
}
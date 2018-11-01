<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;
use Doctrine\Common\Collections\ArrayCollection;


class VideoCollectionEvent extends Event
{
    private $videos;

    public function __construct(ArrayCollection $videos)
    {
        $this->videos = $videos;
    }
    public function getVideos()
    {
        return $this->videos;
    }
}
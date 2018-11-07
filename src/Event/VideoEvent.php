<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;

class VideoEvent extends Event
{
    private $video;

    public function __construct($video)
    {
        $this->video = $video;
    }
    public function getVideo()
    {
        return $this->video;
    }

}
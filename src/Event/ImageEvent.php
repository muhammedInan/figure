<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;

class ImageEvent extends Event
{
    private $image;

    public function __construct($image)
    {
        $this->image = $image;
    }
    public function getImage()
    {
        return $this->image;
    }
    
}
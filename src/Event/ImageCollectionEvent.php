<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;
use Doctrine\Common\Collections\ArrayCollection;


class ImageCollectionEvent extends Event
{
      private $images;

     public function __construct(ArrayCollection $images)
     {
         $this->images = $images;
     }
      public function getImages()
      {
         return $this->images;
      }
}
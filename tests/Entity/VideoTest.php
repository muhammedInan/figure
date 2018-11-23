<?php
namespace App\Tests\Entity;
use PHPUnit\Framework\TestCase;
use App\Entity\Video;
class VideoTest extends TestCase
{
    public function setUp()
    {
                
        
        $this->iframeBegin = "<iframe width='100%' height='100%' src='";
        $this->iframeEnd = "'  frameborder='0'  allowfullscreen></iframe>";
    }
    public function testYoutubeIntegration()
    {
        $video = new Video();
        $video->setUrl('https://www.youtube.com/watch?v=SA7AIQw-7Ms');
        $video->extractIdentif();
        
        $this->assertSame($this->iframeBegin.'https://www.youtube-nocookie.com/embed/SA7AIQw-7Ms'.$this->iframeEnd, $video->video());
        $this->assertSame('https://img.youtube.com/vi/SA7AIQw-7Ms/hqdefault.jpg', $video->image());
    }
    public function testDailyMotionIntegration()
    {
        $video = new Video();
        $video->setUrl('https://www.dailymotion.com/video/x6dzcbf');
        $video->extractIdentif();
        $this->assertSame($this->iframeBegin.'https://www.dailymotion.com/embed/video/x6dzcbf'.$this->iframeEnd, $video->video());
        $this->assertSame('https://www.dailymotion.com/thumbnail/150x120/video/x6dzcbf', $video->image());
    }
    public function testVimeoIntegration()
    {
        $video = new Video();
        $video->setUrl('https://vimeo.com/250495979');
        $video->extractIdentif();
        $this->assertSame($this->iframeBegin.'https://player.vimeo.com/video/250495979'.$this->iframeEnd, $video->video());
        $this->assertRegExp('#https\:\/\/i\.vimeocdn\.com\/video\/([0-9])+_100x75\.jpg#', $video->image());
    }
}
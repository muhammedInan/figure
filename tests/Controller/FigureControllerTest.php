<?php

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FigureControllerTest extends WebTestCase
{private $client;

public function setUp()
{
$this->client = static::createClient();
}

public function testIndex()
{
$this->client->request('GET', '/');

$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
}}


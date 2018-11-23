<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Controller\FigureController;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Figure;
use App\Repository\FigureRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Kernel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use App\Form\FigureType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FigureControllerTest extends WebTestCase
{

    public function setUp()
    {
       $this->client = static::createClient();
       
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $container = $this->client->getContainer();
        
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->controller = new FigureController(
            $this->eventDispatcher
        );
        //$this->container = $this->createMock(ContainerInterface::class);
       // $controller = $this->createMock(TokenStorageInterface::class);
        //$controller->method('getUser')->willReturn(new User());
        $this->controller->setContainer($container);

    }
    
    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        $firewallName = 'main';
        // if you don't define multiple connected firewalls, the context defaults to the firewall name
        // See https://symfony.com/doc/current/reference/configuration/security.html#firewall-context
        $firewallContext = 'secured_area';

        // you may need to use a different token class depending on your application.
        // for example, when using Guard authentication you must instantiate PostAuthenticationGuardToken
        $token = new UsernamePasswordToken('admin', null, $firewallName, array('ROLE_ADMIN'));
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
        $crawler = $this->client->request('GET', '/admin');

    }
    
    public function testIndex()
    {
        $figure = new Figure();
        $figureRepository = $this->createMock(FigureRepository::class);

        $figureRepository->expects($this->any())
            ->method('findAll')
            ->willReturn([$figure]);

        $objectManager = $this->createMock(ObjectManager::class);

        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($figureRepository);


        $response = $this->controller->index(1);
        $this->assertEquals(200,$response->getStatusCode());

    }
    
    public function testCreate()
   {
  
        
       $this->createMock(AccessDeniedException::class);
        $figure = new Figure();
        $form = $this->createMock(FormInterface::class);
        $form->expects($this->any())
            ->method('createView');
                    $this->formFactory->expects($this->any())
            ->method('create')
            ->willReturn($form);
        $request = $this->createMock(Request::class);
        try{
             $response = $this->controller->create($request);
        $this->assertEquals(200,$response->getStatusCode());        
        }catch(\Exception $e){
            
            $this->assertEquals('Access Denied.',$e->getMessage()); 
        }
             
     }
    
     public function testDelete()
    {
     $this->createMock(AccessDeniedException::class);
    $figure = new Figure();
        $form = $this->createMock(FormInterface::class);
        $form->expects($this->any())
            ->method('createView');
              
        $this->formFactory->expects($this->any())
            ->method('create')
            ->willReturn($form);
        $request = $this->createMock(Request::class);
        try{
             $response = $this->controller->delete($request,$figure);
        $this->assertEquals(302,$response->getStatusCode());
        }catch(\Exception $e){
            $this->assertEquals('Access Denied.',$e->getMessage()); 
        }
    }
}


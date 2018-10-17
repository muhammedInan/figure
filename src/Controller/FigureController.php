<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Figure;
use App\Repository\FigureRepository;
use App\Form\FigureType;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Event\Constants\ImageEvents;
use App\Event\Constants\VideoEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Event\ImageCollectionEvent;
use App\Event\VideoCollectionEvent;

class FigureController extends AbstractController
{
    private $eventDispatcher;

    public function  __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/figure", name="figure")
     */
    public function index(FigureRepository $repo, Request $request)
    {
        //$repo = $this->getDoctrine()->getRepository(Figure::class);
        $figures = $repo->findAll();

        return $this->render('figure/index.html.twig', [
            'controller_name' => 'FigureController',
            'figures' => $figures
        ]);
    }
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('figure/home.html.twig');
    }
    /**
     * @Route("/figure/create", name="figure_create", methods={"GET","POST"})
     */
    public function create(Request $request)
    {
        $user = $this->getUser();
        if ($user == null) {
            throw $this->createAccessDeniedException();
        }
        $figure = new Figure();
        $form = $this->createForm(FigureType::class, $figure);
        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $figure->setUser($user);
                $event = new ImageCollectionEvent($figure->getImages());
                $images = $this->eventDispatcher->dispatch(ImageEvents::PRE_UPLOAD, $event);
                $figure->setImages($images->getImages());
                $manager->persist($figure);
                $event = new ImageCollectionEvent($figure->getImages());
                $this->eventDispatcher->dispatch(ImageEvents::POST_UPLOAD, $event);
               
                $manager->flush();
                $this->addFlash(
                    'info',
                    'Added successfully'
                );
                return $this->redirectToRoute('figure_show', ['id' => $figure->getId()]);
            }
        }
        return $this->render('figure/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/figure/{id}/edit", name="figure_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Figure $figure)
    {
        $user = $this->getUser();
        if ($user != $figure->getUser()) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm(FigureType::class, $figure);
        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $event = new ImageCollectionEvent($figure->getImages());
                $this->eventDispatcher->dispatch(ImageEvents::PRE_UPLOAD, $event);
                $manager->persist($figure);
                $event = new ImageCollectionEvent($figure->getImages());
                $this->eventDispatcher->dispatch(ImageEvents::POST_UPLOAD, $event);
                $manager->flush();
                $this->addFlash(
                    'info',
                    'Update successfully'
                );
                return $this->redirectToRoute('figure_show', ['id' => $figure->getId()]);
            }
        }
        return $this->render('figure/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/figure/{id}/delete", name="figure_delete")
     */
    public function delete(Request $request, Figure $figure)
    {
        $user = $this->getUser();
        if ($user != $figure->getUser()) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createDeleteForm($figure);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $event = new ImageCollectionEvent($figure->getImages());
            $this->eventDispatcher->dispatch(ImageEvents::PRE_REMOVE, $event);
            $em->remove($figure);
            $event = new ImageCollectionEvent($figure->getImages());
            $this->eventDispatcher->dispatch(ImageEvents::POST_REMOVE, $event);
            $em->flush();
        }
        return $this->redirectToRoute('figure');
    }
    private function createDeleteForm(Figure $figure)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('figure_delete', array('id' => $figure->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
    /**
     * @Route("/figure/{id}", name="figure_show")
     */
    public function show(Figure $figure, Request $request, ObjectManager $manager)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTime())
                ->setFigure($figure)
                ->setAuthor($this->getUser());
            $manager->persist($comment);
            $manager->flush();
            $this->addFlash(
                'info',
                'Added successfully'
            );
            return $this->redirectToRoute('figure_show', ['id' => $figure->getId()]);
        }

        $deleteForm = $this->createDeleteForm($figure);
        return $this->render('figure/show.html.twig', [
            'figure' => $figure,
            'comments' => $figure->getComments(),
            'commentForm' => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }
}

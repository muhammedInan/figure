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
use App\Entity\Image;
use App\Entity\Video;
use App\Form\CommentType; 
use App\Event\Constants\ImageEvents;
use App\Event\Constants\VideoEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Event\ImageCollectionEvent;
use App\Event\VideoCollectionEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;



class FigureController extends AbstractController
{
    private $eventDispatcher;

    public function  __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/{page}", name="figure", requirements={"page"="\d+"})
     */
    public function index($page = 1)
    {
        $em = $this->getDoctrine()->getManager();
        $allFigures = $em->getRepository(Figure::class)->findAll();
        $nbPages = ceil(count($allFigures) / 10);
        $figures = $em->getRepository(Figure::class)->getPaginateListOfFigures($page);

        return $this->render('figure/index.html.twig', [
            'page' => $page,
            'nb_pages' => $nbPages,
            'figures' => $figures
        ]);
    }
   
    /**
     * @Route("/load-more-comment/{id}/{page}")
     */
    public function loadlistComment(Figure $figure, $page)
    {
        $pagination = $this->getParameter('pagination-comment');
        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository(Comment::class)->getPaginateListOfComments($figure, $pagination, $page);
        return $this->render('figure/show.html.twig', ['comments'=> $comments]);
    }

    /**
     *
     * @Route("/load-more-figure/{page}")
     */
    public function loadListFigure($page)
    {
        $pagination = $this->getParameter('pagination-figure');
        $em = $this->getDoctrine()->getManager();
        $figures = $em->getRepository(Figure::class)->getPaginateListOfFigures($pagination, $page);
        return $this->render('figure/index.html.twig', ['figures'=> $figures]);
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
                //$this->eventDispatcher->dispatch(ImageEvents::PRE_UPLOAD, $event);
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
    public function show(Figure $figure, Request $request, ObjectManager $manager, $page = 1)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $allComments = $em->getRepository(Comment::class)->findByFigure($figure);
        $nbPages = ceil(count($allComments) / 10);
        $commentpagi = $em->getRepository(Comment::class)->getPaginateListOfComments($page, $figure);
        
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
            'page' => $page,
            'nb_pages' => $nbPages,
            'commentpagi' => $commentpagi
        ]);
    }

    /**
     * @Route("/image/{id}/remove", name="image_remove")
     */
    public function removeImage(Image $image)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($image);
        $em->flush();
        return $this->redirectToRoute('figure_show', ['id'=>$image->getFigure()->getId()]);
    }

    /**
     * @Route("/video/{id}/remove", name="video_remove")
     */
    public function removeVideo(Video $video)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($video);
        $em->flush();
        return $this->redirectToRoute('figure_show', ['id'=>$video->getFigure()->getId()]);
    }

}

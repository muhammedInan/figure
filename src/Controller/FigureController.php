<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Figure;
use App\Entity\User;
use App\Repository\FigureRepository;
use App\Form\FigureType;
use App\Entity\Comment;
use App\Form\CommentType;


class FigureController extends AbstractController
{
    /**
     * @Route("/figure", name="figure")
     */
    public function index(FigureRepository $repo)
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
        $figure = new Figure();
        $form = $this->createForm(FigureType::class, $figure);

        if('POST'=== $request->getMethod()){
            $form->handleRequest($request);
            if( $form->isValid()){
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($figure);
               // dump($figure); die;
                $manager->flush();

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

        $form = $this->createForm(FigureType::class, $figure);

        if('POST'=== $request->getMethod()){
            $form->handleRequest($request);
            if( $form->isValid()){
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($figure);
                $manager->flush();
                return $this->redirectToRoute('figure_show', ['id' => $figure->getId()]);
            }
        }

        return $this->render('figure/edit.html.twig', [
            'form' => $form->createView(),


        ]);
    }



    /**
     * @Route("/figure/{id}/deleteAction", name="figure_delete")
     */
    public function deleteAction(Request $request, Figure $figure)
    {
        $form = $this->createDeleteForm($figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($figure);
            $em->flush();
        }

        return $this->redirectToRoute('home');
    }

    private function createDeleteForm(Figure $figure)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('figure_delete', array('id' => $figure->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }





    /**
     * @Route("/figure/{id}", name="figure_show")
     */
    public function show(Figure $figure, Request $request, ObjectManager $manager){

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment->setCreatedAt(new \DateTime())
                    ->setFigure($figure)
                    ->setAuthor($this->getUser());

          $manager->persist($comment);
          $manager->flush();

          return $this->redirectToRoute('figure_show', ['id' => $figure->getId()]);

        }
        $deleteForm = $this->createDeleteForm($figure);
        return $this->render('figure/show.html.twig',[
            'figure' => $figure,
            'commentForm' => $form->createView(),
            'delete_form' => $deleteForm->createView(),
            ]);
    }


}

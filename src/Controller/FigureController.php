<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Figure;
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
     * @Route("/figure/new", name="figure_create")
     * @Route("/figure/{id}/edit", name="figure_edit")
     */
    public function form(Figure $figure = null,Request $request, ObjectManager $manager){

        if(!$figure) {
            $figure = new Figure();
        }

        $figure->setTitle("Titre d'exemple")
               ->setContent("contenu ");

        //$form = $this->createFormBuilder($figure)
        //             ->add('title')
        //             ->add('content')
        //            ->add('image')
        //          ->getForm();

        $form = $this->createForm(FigureType::class, $figure);

        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){
            if(!$figure->getId()){
                $figure->setCreateAt(new \Datetime());
            }


            $manager->persist($figure);
            $manager->flush();

            return $this->redirectToRoute('figure_show', ['id' => $figure->getId()]);

        }


        return $this->render('figure/create.html.twig',[
            'formFigure' => $form->createView(),
            'editMode' => $figure->getId() !== null
        ]);
    }

    /**
     * @Route("/figure/{id}", name="figure_show")
     */
    public function show(Figure $figure, Request $request, ObjectManager $manager){
       // $repo = $this->getDoctrine()->getRepository(Figure::class);

       // $figure = $repo->find($id);
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment->setCreatedAt(new \DateTime())
                    ->setFigure($figure);

          $manager->persist($comment);
          $manager->flush();

          return $this->redirectToRoute('figure_show', ['id' => $figure->getId()]);

        }

        return $this->render('figure/show.html.twig',[
            'figure' => $figure,
            'commentForm' => $form->createView()
            ]);
    }


}

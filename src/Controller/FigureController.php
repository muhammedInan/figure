<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Figure;
use App\Repository\FigureRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TexteaType;

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
     */
    public function create(Request $request, ObjectManager $manager){
        $figure = new Figure();

        $form = $this->createFormBuilder($figure)
                     ->add('title', TextareaType::class)
                     ->add('content', TextType::class)
                     ->add('image')
                     ->getForm();
        return $this->render('figure/create.html.twig',[
            'formFigure' => $form->createView()
        ]);
    }

    /**
     * @Route("/figure/{id}", name="figure_show")
     */
    public function show(Figure $figure){
       // $repo = $this->getDoctrine()->getRepository(Figure::class);

       // $figure = $repo->find($id);

        return $this->render('figure/show.html.twig',[
            'figure' => $figure
            ]);
    }


}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{

    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {

        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login()
    {
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout()
    {
    }

    /**
     * @Route("/resetPassword", name="security_reset_password")
     */
    public function resetPassword(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('email')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $data = $form->getData();
            $user = $em->getRepository(User::class)->findOneByEmail($data['email']);
            $user->setChangePassword(bin2hex(random_bytes(32)));
            $em->persist($user);
            $em->flush();

            $message = new \Swift_Message();
            $message->setSubject('email')
                ->setFrom('muhammed-inan@outlook.com')
                ->setTo('muhammed-inan@outlook.com')
                ->setBody('email', 'text/html');

            return $message;

        }

        $mailer->send($message);
        return $this->render('security/reset_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}


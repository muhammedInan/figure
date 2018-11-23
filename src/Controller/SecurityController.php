<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordType;
use App\Event\Constants\EmailEvents;
use App\Event\EmailEvent;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormError;
use App\Event\Constants\ImageEvents;

use App\Event\ImageCollectionEvent;


class SecurityController extends AbstractController
{

    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, ObjectManager $manager, EventDispatcherInterface $eventDispatcher, UserPasswordEncoderInterface $encoder)
    {

        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = new ImageCollectionEvent([$user->getImage()]);
            $images = $eventDispatcher->dispatch(ImageEvents::PRE_UPLOAD, $event);
            $user->setImage($images->getImages()[0]);
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setValidationToken(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
            $manager->persist($user);
            $event = new ImageCollectionEvent([$user->getImage()]);
            $eventDispatcher->dispatch(ImageEvents::POST_UPLOAD, $event);
            $manager->flush();
            $event = new EmailEvent($user);
            $eventDispatcher->dispatch(EmailEvents::USER_REGISTERED, $event);

            return $this->redirectToRoute('security_registerConfirm');
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
     * @Route("/confirm_user/{token}", name="security_confirmUser")
     */
    public function confirmUser($token)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneByValidationToken($token);
        if (is_null($user)) {
            throw new NotFoundHttpException('Token invalide');
        } else {
            $user->setValidationToken(null);
            $user->setIsActive(true);
            $em->persist($user);
            $em->flush();
            return $this->render(
                'security/confirm_user.html.twig',
                []
            );
        }
    }

    /**
     * @Route("/forgot_password", name="security_forgotPassword")
     */
    public function forgotPassword(Request $request, EventDispatcherInterface $eventDispatcher)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form->getData()['username'];
            $user = $em->getRepository(User::class)->findOneByUsername($username);
            if (is_null($user)) {
                $form->get('username')->addError(new FormError("Ce pseudo n'existe pas"));
            } else {
                $user->setResetToken(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
                $em->persist($user);
                $em->flush();
                $event = new EmailEvent($user);
                $eventDispatcher->dispatch(EmailEvents::FORGOT_PASSWORD, $event);
                return $this->redirectToRoute('security_forgotPasswordConfirm');
            }
        }
        return $this->render(
            'Password/forgot_password.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/reset_password/{token}", name="security_resetPassword")
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, $token)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneByResetToken($token);

        if (is_null($user)) {
            throw new NotFoundHttpException('Token invalide');
        }
        $emailUser = $user->getEmail();
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($emailUser != $form->getData()['email']) {
                $form->get('email')->addError(new FormError("Ce n'est pas la bonne adresse email"));
            } else {
                $password = $passwordEncoder->encodePassword($user, $form->getData()['password']);
                $user->setPassword($password);
                $user->setResetToken(null);
                $em->persist($user);
                $em->flush();
                $this->addFlash('add_tricks_success', 'Le mot de passe à été réinitialisé');
                return $this->redirectToRoute('security_login');
            }
        }
        return $this->render(
            'Password/reset_password.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/reset_password/{token}", name="security_resetPasswordConfirm")
     */
    public function resetPasswordConfirm()
    {
        return $this->render('Password/reset_password_confirm.html.twig');
    }

    /**
     * @Route("/forgot_password_confirm", name="security_forgotPasswordConfirm")
     */
    public function forgotPasswordConfirm()
    {
        return $this->render('Password/forgot_password_confirm.html.twig');
    }

    /**
     * @Route("/reset_password_confirm", name="security_registerConfirm")
     */
    public function registerConfirm()
    {
        return $this->render('security/register_confirm.html.twig');
    }
}


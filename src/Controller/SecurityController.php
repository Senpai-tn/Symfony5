<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if($this->getUser() != null)
        {
            $this->addFlash('success',"You are already logged in");
            return $this->redirectToRoute('home');
        }
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/register",name="register",methods={"GET","POST"})
     */
    public function register(Request $r,UserPasswordEncoderInterface $encoder)
    {
        if($this->getUser() != null)
        {
            $this->addFlash('success',"You are already logged in");
            return $this->redirectToRoute('home');
        }
        $form = $this->createFormBuilder()
            ->add("username",TextType::class,['label'=>"Username : "])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password']
            ])
            ->getForm()
        ;
        $form->handleRequest($r);
        if(($form->isSubmitted())&&($form->isValid()))
        {
            try {
                $data = $form->getData();
                $user = new User();
                $user->setUsername($data['username']);
                $user->setPassword($encoder->encodePassword($user,$data['password']));
                $user->setRoles(['ROLE_USER']);
                $user->setFirstname("Khaled");
                $user->setLastname("Sahli");
                $user->setCreatedAt(new \DateTime());
                $this->em->persist($user);
                $this->em->flush();
                $session = $r->getSession();
                $session->set('userId',$user->getId());
                $this->addFlash('success', 'Register sucessefully');
                return $this->redirectToRoute('app_login',["form"=>$form->createView(),"success"=>true]);
            }
            catch (\Exception $e)
            {

                if($e->getCode() == 0)
                    $message = "username does exist";
                else
                    $message = "unkown error ";
                return $this->render('security/register.html.twig',["form"=>$form->createView(),"errors"=>$message]);
            }


        }
        else
            return $this->render('security/register.html.twig',["form"=>$form->createView(),"errors"=>[]]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


}

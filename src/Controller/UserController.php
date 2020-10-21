<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em ;
    }

    /**
     * @Route("/@{username}", name="user")
     */
    public function index($username)
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['username' =>$username]);
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }
}

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
     * @Route("/profile/{username}", name="user")
     */
    public function index($username)
    {
        dd($this->getUser());
    }
}

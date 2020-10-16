<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{


    /**
     * @Route("/", name="posts",methods="GET|POST")
     */
    public function index(Request $r,EntityManagerInterface $em)
    {
//        $form = $this->createFormBuilder()
//            ->add("title")
//            ->add('submit',SubmitType::class)
//            ->getForm()
//        ;
//        $form->handleRequest($r);
//        if(($form->isSubmitted())&&($form->isValid()))
//        {
//            return $this->json(["ff"=>"sdfdsf"]);
//        }
//        return $this->render('posts/index.html.twig', [
//            'controller_name' => 'PostsController',
//            'form'=>$form->createView()
//        ]);
 
        $posts = $em->getRepository(Post::class)->findAll();

//        return new Response(count($posts));
        return $this->render('base.html.twig');
    }
}

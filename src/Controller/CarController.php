<?php

namespace App\Controller;

use App\Entity\Car;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends AbstractController
{
    public $em;
    public function __construct(EntityManagerInterface  $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/car/{id}", name="car")
     */
    public function index($id)
    {
        $car = $this->em->getRepository(Car::class)->find($id);
        dd($car);
    }

    /**
     * @Route("/car/date/{date}",name="reservation")
     */
    public function reserve($date)
    {
        $cars = $this->em->getRepository(Car::class)->findByDate($date);
        return $this->json($cars);
    }


}

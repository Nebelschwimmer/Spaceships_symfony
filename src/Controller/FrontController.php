<?php

namespace App\Controller;

use App\Filter\SpaceshipFilter;
use App\Entity\SpaceShip;
use App\Filter\SpaceshipFilterType;
use App\Form\SpaceShipType;
use App\Repository\SpaceShipRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FrontController extends AbstractController
{
  #[Route('/', name: 'app_font_index', methods: ['GET'])]
     
    public function index(SpaceShipRepository $spaceShipRepository): Response
    {
      return $this->redirectToRoute(route: 'app_space_ship_index');
    }
}
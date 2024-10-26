<?php

namespace App\Controller;

use App\Entity\SpaceShipCategory;
use App\Entity\SpaceShip;
use App\Repository\SpaceShipRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Repository\SpaceShipCategoryRepository;
use App\Entity\Publication;
use App\Entity\Tag;


final class ApiController extends AbstractController
{
  #[Route('/api/spaceships', name: 'api_space_ships', methods: ['GET'])]

  public function index(SpaceShipRepository $spaceShipRepository, Request $request): Response
  {

    $offset = $request->query->getInt('offset', 0);
    $limit = $request->query->getInt('limit', 8);
    $searchQuery = $request->query->get('search', null);
    $spaceShips = $spaceShipRepository->findAllWithQueryParams($offset, $limit, $searchQuery);
    $allSpaceships = $spaceShipRepository->findAll();
    
    $totalSpaceshipsListNumber = count($allSpaceships);
    $response = new Response();
    $encoders = [new XmlEncoder(), new JsonEncoder()];
    $normalizers = [new ObjectNormalizer()];
    $serializer = new Serializer($normalizers, $encoders);
    $maxPages = floor( $totalSpaceshipsListNumber / $limit) + 1;
    $jsonContent = $serializer->serialize(['spaceships' => $spaceShips, 'maxpages' => $maxPages, 'total' => $totalSpaceshipsListNumber], 'json');

    $response->headers->set('Content-Type', 'application/json');
    $response->setContent($jsonContent);

    return $response;
  }

  #[Route('/api/spaceships/list', name: 'api_spaceship_list', methods: ['GET'])]
  public function listSpaceships( SpaceShipRepository $spaceShipRepository): Response
  {

    $response = new Response();
    $response->headers->set('Content-Type', 'application/json');
    return $this->json($spaceShipRepository->findAll());
  }



  #[Route('/api/spaceships/{id}', name: 'api_space_ship_show', methods: ['GET'])]
  public function show(SpaceShip $spaceShip): Response
  {
    $response = new Response();
    $response->headers->set('Content-Type', 'application/json');

    return $this->json($spaceShip);
  }

  #[Route('/api/spaceships/{id}', name: 'api_spaceship_delete_one', methods: ['DELETE'])]
  public function deleteSpaceshipById(Request $request, SpaceShip $spaceShip, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
  {
    $userId = $request->getPayload()->get('userId');

    $user = $userRepository->findUserById($userId);
    $spaceShip->getId();
    $entityManager->remove($spaceShip);
    $publication = $user->findPublicationBySpaceShipId($spaceShip->getId());
    if ($publication) {
      $user->removePublication($publication);
      $entityManager->remove($publication);
    }
    $entityManager->flush();
    $response = new Response();
    $response->headers->set('Content-Type', 'application/json');
    return $this->json(['status' => 'deleted']);
  }
  
  #[Route('/api/spaceships/delete/all', name: 'api_spaceship_delete_all', methods: ['DELETE'])]
  public function deleteAllSpaceships(SpaceShipRepository $spaceShipRepository, EntityManagerInterface $entityManager): Response
  {
    $spaceShips = $spaceShipRepository->findAll();
    foreach ($spaceShips as $spaceShip) {
      $entityManager->remove($spaceShip);
    }
    $entityManager->flush();
    $response = new Response();
    $response->headers->set('Content-Type', 'application/json');
    return $this->json(['status' => 'deleted']);
  }
  
  
  
  #[Route('/api/spaceships/categories/list', name: 'api_spaceship_categories_list', methods: ['GET'])]
  public function listCategories(SpaceShipCategoryRepository $spaceShipCategoryRepository): Response
  {
    $response = new Response();
    $response->headers->set('Content-Type', 'application/json');

    return $this->json($spaceShipCategoryRepository->findAll());
  }




  
  #[Route('/api/spaceships/add', name: 'api_spaceship_add', methods: ['POST'])]

  public function addSpaceship(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, SpaceShipCategoryRepository $spaceShipCategoryRepository, TagRepository $tagRepository): Response
  {
    $user = $userRepository->findUserById($request->getPayload()->get('userId'));
    if (!$user) {
      return $this->json(['message' => 'User not found.'], Response::HTTP_NOT_FOUND);
    }
    $tagsFromTheClient = $request->getPayload()->get('tags');
    $transformedTags = $this->transformTags($tagsFromTheClient, $tagRepository);
    $spaceShip = new SpaceShip($user);
    $category = new SpaceShipCategory;
    $category = $spaceShipCategoryRepository->find($request->getPayload()->get('categoryId'));
    $spaceShip
      ->setName($request->getPayload()->get('name'))
      ->setImage($request->getPayload()->get('image'))
      ->setCategory($category)
      ->setTags($transformedTags);
      $entityManager->persist($spaceShip);
    $entityManager->flush();
    $response = new Response();
    $response->headers->set('Content-Type', 'application/json');
    return $this->json(['message' => 'SpaceShip added successfully.']);

  }
  #[Route('/api/spaceships/{id}/edit', name: 'api_spaceship_edit', methods: ['POST', 'PUT'])]

  public function editSpaceship(Request $request, EntityManagerInterface $entityManager, SpaceShipRepository $spaceShipRepository, SpaceShipCategoryRepository $spaceShipCategoryRepository, TagRepository $tagRepository, ): Response
  {
    $spaceShip = $spaceShipRepository->find($request->getPayload()->get('id'));
    $category = new SpaceShipCategory;
    $category = $spaceShipCategoryRepository->find($request->getPayload()->get('categoryId', 1));
    $tagsFromTheClient = $request->getPayload()->get('tags') ?? [];
    $transformedTags = $this->transformTags($tagsFromTheClient, $tagRepository);

    $spaceShip
      ->setName($request->getPayload()->get('name'))
      ->setDescription($request->getPayload()->get('description'))
      ->setImage($request->getPayload()->get('image'))
      ->setCategory($category)
      ->setTags($transformedTags);
    $entityManager->persist($spaceShip);
    $entityManager->flush();
    $response = new Response();
    $response->headers->set('Content-Type', 'application/json');
    return $this->json(['message' => 'SpaceShip edited successfully.']);
  }


  private function transformTags(mixed $value = null, TagRepository $tagRepository): ArrayCollection
  {
    if (!$value) {
      return new ArrayCollection();
    }

    $items = explode(",", $value);
    $items = array_map('trim', $items);
    $items = array_unique($items);

    $tags = new ArrayCollection();

    foreach ($items as $item) {
      $tag = $tagRepository->findOneBy(['name' => $item]);
      if (!$tag) {
        $tag = (new Tag())->setName($item);
      }

      $tags->add($tag);
    }

    return $tags;
  }
}
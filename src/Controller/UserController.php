<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{

  #[Route('/api/user/list', name: 'api_user_list', methods: ['GET'])]
  public function index(UserRepository $userRepository): Response
  {
    $response = new Response();
    $response->headers->set('Content-Type', 'application/json');
    return $this->json($userRepository->findAll());
  }
  #[Route('/api/user/{id}', name: 'api_user_show', methods: ['GET'])]
  public function show(UserRepository $userRepository, int $id): Response
  {
    $response = new Response();
    $response->headers->set('Content-Type', 'application/json');

    return $this->json($userRepository->findUserById($id));
  }
  



  #[Route('/api/user/{id}/delete', name: 'api_user_delete', methods: ['DELETE'])]
  public function delete(User $user, EntityManagerInterface $entityManager, UserRepository $userRepository, int $id): Response
  {
    $user = $userRepository->findUserById($id);
    $entityManager->remove($user);
    $entityManager->flush();
    $response = new Response();
    $response->headers->set('Content-Type', 'application/json');
    return $this->json(['status' => 'deleted']);
  }

  #[Route('/api/user/{id}/edit', name: 'api_user_edit', methods: ['POST', 'PUT'])]
  public function edit(User $user, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, int $id): Response
  {
    $user = $userRepository->findUserById($id);
    $username = $request->getPayload()->get('username');
    $avatarURL = $request->getPayload()->get('avatar');
    if ($username) {
      $user->setUsername($username);
    }
    if ($avatarURL) {
      $user->setAvatar($avatarURL);
    }
    $entityManager->persist($user);
    $entityManager->flush();
    $response = new Response();
    $response->headers->set('Content-Type', 'application/json');
    return $this->json($user);
  }

}
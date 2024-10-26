<?php

namespace App\Controller;

use App\Entity\Publication;
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




final class SpaceShipController extends AbstractController
{

    #[Route('/admin', name: 'app_space_ship_index', methods: ['GET'])]
    public function index(Request $request, SpaceShipRepository $spaceShipRepository): Response
    {
        $spaceshipFilter = new SpaceshipFilter();
        $form = $this->createForm(SpaceshipFilterType::class, $spaceshipFilter);
        $form->handleRequest($request);
        // $spaceShips = $spaceShipRepository->findAll();
        // return $this->json( $spaceShips);
        return $this->render('space_ship/index.html.twig', [
            'space_ships' => $spaceShipRepository->findByFilter($spaceshipFilter),
            'form' => $form->createView(),
        ]);
    }



    #[Route('admin/new', name: 'app_space_ship_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $spaceShip = new SpaceShip($this->getUser());
        $form = $this->createForm(SpaceShipType::class, $spaceShip);
        $form->handleRequest($request);
        $user = $this->getUser();
        $publication = new Publication();
        ;
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($spaceShip);
            $entityManager->persist($user);
            $publication
                ->setName($spaceShip->getName())
                ->setSpaceshipId($spaceShip->getId())
                ->setType('space_ship')
                ->setCreatedAt(new \DateTime());
            $user->addPublication($publication);
            $entityManager->flush();
            return $this->redirectToRoute('app_space_ship_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('space_ship/new.html.twig', [
            'space_ship' => $spaceShip,
            'form' => $form,
        ]);
    }

    #[Route('admin/{id}', name: 'app_space_ship_show', methods: ['GET'])]
    public function show(SpaceShip $spaceShip): Response
    {
        return $this->render('space_ship/show.html.twig', [
            'space_ship' => $spaceShip,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_space_ship_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SpaceShip $spaceShip, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SpaceShipType::class, $spaceShip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_space_ship_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('space_ship/edit.html.twig', [
            'space_ship' => $spaceShip,
            'form' => $form,
        ]);
    }

    #[Route('admin/{id}', name: 'app_space_ship_delete', methods: ['POST'])]
    public function delete(Request $request, SpaceShip $spaceShip, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $spaceShip->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($spaceShip);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_space_ship_index', [], Response::HTTP_SEE_OTHER);
    }
}

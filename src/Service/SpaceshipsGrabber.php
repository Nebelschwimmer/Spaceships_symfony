<?php

namespace App\Service;
use App\Repository\SpaceShipCategoryRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\Entity;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DomCrawler\Crawler;
use App\Entity\SpaceShip;
use Doctrine\ORM\EntityManagerInterface;


class SpaceshipsGrabber
{
  private ?LoggerInterface $logger = null;
  public function __construct(private readonly EntityManagerInterface $entityManager, private readonly UserRepository $userRepository, private readonly ParameterBagInterface $parameterBag, private readonly SpaceShipCategoryRepository $spaceShipCategoryRepository)
  {
  }
  public function setLogger(LoggerInterface $logger): self
  {
    $this->logger = $logger;

    return $this;
  }
  public function importSpaceships(?int $count = null, bool $dryRun = false): void
  {
    $this->logger->notice('Importing spaceships...');

    $client = new Client([
      'timeout' => 10.0,
    ]);
    $spaceshipNames = [];
    $spaceshipDescriptions = [];
    $spaceshipPictures = [];

    $response = $client->get('https://www.cnet.com/pictures/may-the-4th-the-41-most-powerful-star-wars-spaceships-ranked/');
    $crawler = new Crawler($response->getBody()->getContents());
    $crawler->filter('h2')->each(function (Crawler $crawLer) use (&$spaceshipNames, $count): void {
      if ($count && count($spaceshipNames) >= $count) {
        return;
      }
      $spaceshipNames[] = [
        'name' => $crawLer->text(),
      ];


    });
    $this->logger->info(sprintf('Found %d spaceships', count($spaceshipNames)));

    $crawler->filter('p')->each(function (Crawler $crawLer) use (&$spaceshipDescriptions, $count): void {
      if ($count && count($spaceshipDescriptions) >= $count) {
        return;
      }
      $spaceshipDescriptions[] = [
        'description' => $crawLer->text(),
      ];
    });


   
      $crawler->filter('img')->each(function (Crawler $crawLer) use (&$spaceshipPictures, $count): void {
        if ($count && count($spaceshipPictures) >= $count) {
          return;
        }
        if($crawLer->attr(attribute: 'loading') !== 'lazy') {
          $spaceshipPictures[] = [
            'picture' => $crawLer->attr('src'),
          ];
      } else {
        return;
      }

       
      });

    unset($crawler);

    for ($i = 0; $i < count($spaceshipNames); $i++) {
      $spaceShip = $this->createSpaceShip($spaceshipNames[$i]['name'], $spaceshipDescriptions[$i]['description'], $spaceshipPictures[$i]['picture']);
      if (!$spaceShip) {
        $this->logger->error('SpaceShip not created because user was not found');
        continue;
      }


      if ($this->checkSpaceshipExists($spaceShip)) {
        $this->logger->warning(sprintf('SpaceShip %s already exists', $spaceShip->getName()));
        continue;
      }

      if ($dryRun) {
        $this->logger->info(sprintf('SpaceShip %s would be created', $spaceShip->getName()));
        continue;
      }
      $this->logger->info(sprintf('SpaceShip %s created', $spaceShip->getName()));
      $this->entityManager->persist($spaceShip);
      $this->entityManager->flush();
    }
    $this->logger->info('All spaceships imported');
    unset($spaceshipNames);
  }
  private function createSpaceShip($name, $description, $picture): SpaceShip|null
  {
    $fakeUser = $this->userRepository->find($this->parameterBag->get('fake_user_id'));
    if (!$fakeUser) {
      $this->logger->alert('User not found');
      return null;
    }
    $spaceShip = new SpaceShip($fakeUser);
    $spaceShip->setName($name);
    $spaceShip->setDescription($description);
    $spaceShip->setCategory($this->spaceShipCategoryRepository->find(1));
    $spaceShip->setImage($picture);

    return $spaceShip;
  }

  private function checkSpaceshipExists(SpaceShip $spaceShip): bool
  {
    return $this->entityManager->getRepository(SpaceShip::class)->findOneBy(['name' => $spaceShip->getName()]) !== null;
  }

}

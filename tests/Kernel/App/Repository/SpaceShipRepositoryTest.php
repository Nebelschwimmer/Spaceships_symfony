<?php

namespace App\Tests\Kernel\App\Repository;

use App\Factory\SpaceShipFactory;
use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Repository\SpaceShipRepository;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;


class SpaceShipRepositoryTest extends KernelTestCase
{
    use ResetDatabase, Factories;

    public function testSomething(): void
    {
        self::bootKernel();
        $user = UserFactory::createOne();

        SpaceShipFactory::createMany(3, [ 
            'user' =>  $user,]);

        $repository = self::getContainer()->get(SpaceShipRepository::class);
        $this->assertCount(3, $repository->findAll());
    }
}

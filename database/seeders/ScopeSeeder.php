<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Factories\ScopeFactoryInterface;
use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Database\Seeder;

class ScopeSeeder extends Seeder
{
    private ScopeFactoryInterface $scopeFactory;

    private EntityManagerInterface $entityManager;

    public function __construct(
        ScopeFactoryInterface $scopeFactory,
        EntityManager $entityManager
    ) {
        $this->scopeFactory = $scopeFactory;
        $this->entityManager = $entityManager;
    }

    public function run(): void
    {
        foreach (SpotifyAuthenticationApiInterface::SCOPE_ENABLED_MAP as $scope => $enabled) {
            if ($enabled) {
                $scopeModel = $this->scopeFactory->create($scope);

                $this->entityManager->persist($scopeModel);
            }
        }

        $this->entityManager->flush();
    }
}

<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Factories\ScopeFactoryInterface;
use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SeedScopesCommand extends Command
{
    private ScopeFactoryInterface $scopeFactory;

    private EntityManagerInterface $entityManager;

    public function __construct(
        ScopeFactoryInterface $scopeFactory,
        EntityManager         $entityManager
    )
    {
        parent::__construct();

        $this->scopeFactory = $scopeFactory;
        $this->entityManager = $entityManager;
    }

    public function configure()
    {
        $this->setName('spotify:create-scopes');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $counter = 0;

        foreach (SpotifyAuthenticationApiInterface::SCOPE_ENABLED_MAP as $scope => $enabled) {
            $scope = $this->scopeFactory->create($scope);

            $this->entityManager->persist($scope);

            $counter++;
        }

        $this->entityManager->flush();

        $output->writeln("Created {$counter} scopes in database!");

        return self::SUCCESS;
    }
}
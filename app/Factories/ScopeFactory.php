<?php

declare(strict_types=1);

namespace App\Factories;

use App\Entities\ScopeInterface;

class ScopeFactory extends EntityFactory implements ScopeFactoryInterface
{
    public function create(string $scopeName): ScopeInterface
    {
        /** @var ScopeInterface $scope */
        $scope = $this->createNew();

        $scope->setName($scopeName);

        return $scope;
    }
}
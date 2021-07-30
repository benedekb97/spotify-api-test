<?php

declare(strict_types=1);

namespace App\Http\Api\Authentication\Responses\Entity;

use Illuminate\Support\Collection;

class Scope
{
    /** @var Collection|string[] */
    private Collection $scope;

    public function __construct()
    {
        $this->scope = collect();
    }

    public function addScope(string $scope): void
    {
        if (!$this->scope->contains($scope)) {
            $this->scope->add($scope);
        }
    }

    public function getScope(): Collection
    {
        return $this->scope;
    }
}

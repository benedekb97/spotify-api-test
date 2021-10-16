<?php

declare(strict_types=1);

namespace App\Http\Api\Providers;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class RequiredScopesProvider implements RequiredScopesProviderInterface
{
    public function provide(): Collection
    {
        $scopes = new ArrayCollection();

        foreach (config('spotify.requests') as $requestClass) {
            foreach ($requestClass::getScopes() as $scopeName) {
                if (!$scopes->contains($scopeName)) {
                    $scopes->add($scopeName);
                }
            }
        }

        return $scopes;
    }
}

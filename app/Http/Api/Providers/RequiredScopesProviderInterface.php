<?php

declare(strict_types=1);

namespace App\Http\Api\Providers;

use Doctrine\Common\Collections\Collection;

interface RequiredScopesProviderInterface
{
    public function provide(): Collection;
}

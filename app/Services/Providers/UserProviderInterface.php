<?php

declare(strict_types=1);

namespace App\Services\Providers;

use App\Entities\UserInterface;

interface UserProviderInterface
{
    public function provide(): UserInterface;
}

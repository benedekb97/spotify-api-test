<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Http\Api\Events\UpdateProfileEvent;
use App\Services\Providers\Spotify\ProfileProvider;
use App\Services\Providers\Spotify\ProfileProviderInterface;

class UpdateProfileListener
{
    private ProfileProviderInterface $profileProvider;

    public function __construct(
        ProfileProvider $profileProvider
    )
    {
        $this->profileProvider = $profileProvider;
    }

    public function handle(UpdateProfileEvent $event): void
    {
        $this->profileProvider->provide($event->getUser());
    }
}

<?php

declare(strict_types=1);

namespace App\Services\Providers;

use App\Entities\Spotify\AlbumInterface;
use App\Entities\UserInterface;

interface AlbumStatisticsProviderInterface
{
    public function provideForUser(AlbumInterface $album, UserInterface $user);
}

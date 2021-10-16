<?php

declare(strict_types=1);

namespace App\Services\Providers;

use App\Entities\Spotify\ArtistInterface;
use Doctrine\Common\Collections\Collection;

interface ArtistTracksProviderInterface
{
    public function provide(ArtistInterface $artist): Collection;
}

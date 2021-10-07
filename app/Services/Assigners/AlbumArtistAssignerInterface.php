<?php

declare(strict_types=1);

namespace App\Services\Assigners;

use App\Entities\Spotify\AlbumInterface;
use Doctrine\Common\Collections\Collection;

interface AlbumArtistAssignerInterface
{
    public function assign(AlbumInterface $album, Collection $artists): void;
}

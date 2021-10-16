<?php

declare(strict_types=1);

namespace App\Services\Synchronizers;

use App\Entities\Spotify\AlbumInterface;

interface AlbumSynchronizerInterface
{
    public function synchronize(AlbumInterface $album): AlbumInterface;
}

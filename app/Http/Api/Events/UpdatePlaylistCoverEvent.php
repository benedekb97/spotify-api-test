<?php

declare(strict_types=1);

namespace App\Http\Api\Events;

use App\Http\Api\Responses\ResponseBodies\Entity\Image;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;
use LogicException;

class UpdatePlaylistCoverEvent
{
    use Dispatchable;

    private string $playlistId;

    private Collection $images;

    public function __construct(
        string $playlistId,
        $images
    ) {
        $this->playlistId = $playlistId;

        if ($images === null) {
            $this->images = new Collection();
        }

        if ($images instanceof Collection) {
            if ($first = $images->first() instanceof Image || $images->isEmpty()) {
                $this->images = $images;
            } else {
                Log::error(
                    sprintf(
                        'Incompatible entity type in collection: %s in %s',
                        is_object($first) ? get_class($first) : $first,
                        get_class($this)
                    )
                );
            }
        }

        if ($images instanceof Image) {
            $this->images = new Collection([$images]);
        }
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function getPlaylistId(): string
    {
        return $this->playlistId;
    }
}

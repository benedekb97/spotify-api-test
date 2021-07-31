<?php

declare(strict_types=1);

namespace App\Http\Api\Events;

use App\Http\Api\Responses\ResponseBodies\Entity\Track;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use LogicException;

class UpdateTracksEvent
{
    use Dispatchable;

    /**
     * UpdateTracksEvent constructor.
     * @param Track|Collection|null $tracks
     */
    public function __construct($tracks)
    {
        $this->tracks = new Collection();

        if ($tracks === null) {
            return;
        }

        if ($tracks instanceof Track) {
            $this->tracks = new Collection([$tracks]);
        } elseif ($tracks instanceof Collection) {
            if (($first = $tracks->first()) === null || !$first instanceof Track) {
                $this->tracks = new Collection();

                return;
            }

            $this->tracks = $tracks;
        } else {
            throw new LogicException(
                sprintf(
                'Unexpected type for \'tracks\' in %s',
                    get_class($this)
                )
            );
        }
    }

    private Collection $tracks;

    public function getTracks(): Collection
    {
        return $this->tracks;
    }
}

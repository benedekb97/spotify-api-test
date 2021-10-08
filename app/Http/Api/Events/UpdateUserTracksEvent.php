<?php

declare(strict_types=1);

namespace App\Http\Api\Events;

use App\Entities\UserInterface;
use Doctrine\Common\Collections\Collection;
use Illuminate\Foundation\Events\Dispatchable;

class UpdateUserTracksEvent
{
    use Dispatchable;

    private Collection $tracks;

    private UserInterface $user;

    public function __construct(
        UserInterface $user,
        Collection $tracks
    ) {
        $this->tracks = $tracks;
        $this->user = $user;
    }

    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Api\Requests;

use App\Http\Api\Events\UpdatePlaylistTracksEvent;
use App\Http\Api\Events\UpdateTracksEvent;
use App\Http\Api\Factories\ResponseBodies\GetPlaylistItemsResponseBodyFactory;
use App\Http\Api\Responses\ResponseBodies\Entity\PlaylistTrack;
use App\Http\Api\Responses\ResponseBodies\Entity\Track;
use App\Http\Api\Responses\ResponseBodies\GetPlaylistItemsResponseBody;
use App\Http\Api\Responses\SpotifyResponseInterface;
use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class GetPlaylistItemsRequest extends AbstractSpotifyRequest implements SpotifyRequestInterface
{
    private const ENDPOINT_SCHEMA = 'v1/playlists/%s/tracks?limit=%d&offset=%d';

    private string $playlistId;

    private int $offset;

    private int $limit;

    public function __construct(
        string $playlistId,
        int $offset = 0,
        int $limit = 100
    ) {
        parent::__construct();

        $this->playlistId = $playlistId;
        $this->offset = $offset;
        $this->limit = $limit;
    }

    public function getScopes(): array
    {
        return [];
    }

    protected function getEndpoint(): string
    {
        return sprintf(
            self::ENDPOINT_SCHEMA,
            $this->playlistId,
            $this->limit,
            $this->offset
        );
    }

    protected function getMethod(): string
    {
        return self::METHOD_GET;
    }

    protected function getExpectedStatusCode(): ?int
    {
        return SpotifyResponseInterface::STATUS_CODE_OK;
    }

    protected function validateStatusCode(Response $response): bool
    {
        return true;
    }

    protected function getEvents(): array
    {
        return [
            UpdateTracksEvent::class => $this->getUpdateTracksEventParameters(),
            UpdatePlaylistTracksEvent::class => $this->getUpdatePlaylistTracksEventParameters(),
        ];
    }

    private function getUpdateTracksEventParameters(): array
    {
        /** @var GetPlaylistItemsResponseBody $responseBody */
        $responseBody = $this->getResponse()->getBody();

        if ($responseBody === null) {
            return [null];
        }

        $tracks = new Collection();

        /** @var PlaylistTrack $playlistTrack */
        foreach ($responseBody->getItems() as $playlistTrack) {
            if ($playlistTrack->getTrack() instanceof Track) {
                $tracks->add($playlistTrack->getTrack());
            }
        }

        if ($tracks->isEmpty()) {
            Log::debug(json_encode($responseBody->toArray()));
        }

        return [$tracks];
    }

    private function getUpdatePlaylistTracksEventParameters(): array
    {
        /** @var GetPlaylistItemsResponseBody $responseBody */
        $responseBody = $this->getResponse()->getBody();

        if ($responseBody === null) {
            return [$this->playlistId, null];
        }

        return [$this->playlistId, $responseBody->getItems()];
    }

    public function getRequestBodyFactoryClass(): ?string
    {
        return null;
    }

    public function getResponseBodyFactoryClass(): ?string
    {
        return GetPlaylistItemsResponseBodyFactory::class;
    }
}

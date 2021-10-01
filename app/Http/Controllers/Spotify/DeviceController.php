<?php

declare(strict_types=1);

namespace App\Http\Controllers\Spotify;

use App\Http\Api\Requests\GetAvailableDevicesRequest;
use App\Http\Api\Requests\TransferPlaybackRequest;
use App\Http\Api\SpotifyApi;
use App\Http\Api\SpotifyApiInterface;
use App\Http\Controllers\Controller;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DeviceController extends Controller
{
    private SpotifyApiInterface $spotifyApi;

    public function __construct(
        SpotifyApi $spotifyApi,
        EntityManager $entityManager
    ) {
        $this->spotifyApi = $spotifyApi;

        parent::__construct($entityManager);
    }

    public function listAvailable(): Response
    {
        $devices = $this->spotifyApi->execute(new GetAvailableDevicesRequest());

        return new JsonResponse($devices->getBody()->toArray());
    }

    public function activate(string $device, bool $play = false): Response
    {
        $this->spotifyApi->execute(new TransferPlaybackRequest(), [$device], $play);

        return new JsonResponse(null);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\RecommendationsRequest;
use App\Repositories\PlaybackRepositoryInterface;
use Doctrine\ORM\EntityManager;

class DashboardController extends Controller
{
    private PlaybackRepositoryInterface $playbackRepository;

    public function __construct(
        EntityManager $entityManager,
        PlaybackRepositoryInterface $playbackRepository
    ){
        parent::__construct($entityManager);

        $this->playbackRepository = $playbackRepository;
    }

    public function index()
    {
        return view('pages.dashboard.index');
    }

    public function history()
    {
        $playbacks = $this->playbackRepository->getRecentPlaybacksByUser($this->getUser());

        return view(
            'pages.dashboard.history',
            [
                'playbacks' => $playbacks
            ]
        );
    }

    public function recommendations(RecommendationsRequest $request)
    {

    }
}

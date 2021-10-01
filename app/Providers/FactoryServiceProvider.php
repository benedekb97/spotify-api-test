<?php

declare(strict_types=1);

namespace App\Providers;

use App\Entities\Spotify\Album;
use App\Entities\Spotify\Artist;
use App\Entities\Spotify\Playback;
use App\Entities\Spotify\Playlist;
use App\Entities\Spotify\PlaylistTrack;
use App\Entities\Spotify\Track;
use App\Entities\User;
use App\Factories\AlbumFactory;
use App\Factories\ArtistFactory;
use App\Factories\PlaybackFactory;
use App\Factories\PlaylistFactory;
use App\Factories\PlaylistTrackFactory;
use App\Factories\TrackFactory;
use App\Factories\UserFactory;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class FactoryServiceProvider extends ServiceProvider
{
    private const ENTITY_FACTORY_MAP = [
        Album::class => AlbumFactory::class,
        Artist::class => ArtistFactory::class,
        Playback::class => PlaybackFactory::class,
        Playlist::class => PlaylistFactory::class,
        PlaylistTrack::class => PlaylistTrackFactory::class,
        Track::class => TrackFactory::class,
        User::class => UserFactory::class,
    ];

    public function register(): void
    {
        foreach (self::ENTITY_FACTORY_MAP as $entity => $factory) {
            $this->app->bind(
                $factory,
                function (Application $app) use ($entity, $factory) {
                    return new $factory($entity);
                }
            );

            $this->app->bind(
                $factory . 'Interface',
                function (Application $app) use ($entity, $factory) {
                    return new $factory($entity);
                }
            );
        }
    }
}

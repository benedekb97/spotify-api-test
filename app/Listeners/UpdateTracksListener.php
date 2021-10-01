<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Entities\Spotify\AlbumInterface;
use App\Entities\Spotify\ArtistInterface;
use App\Entities\Spotify\TrackInterface;
use App\Factories\AlbumFactoryInterface;
use App\Factories\ArtistFactoryInterface;
use App\Factories\TrackFactoryInterface;
use App\Http\Api\Events\UpdateTracksEvent;
use App\Http\Api\Responses\ResponseBodies\Entity\Album as AlbumEntity;
use App\Http\Api\Responses\ResponseBodies\Entity\Artist as ArtistEntity;
use App\Http\Api\Responses\ResponseBodies\Entity\Copyright;
use App\Http\Api\Responses\ResponseBodies\Entity\Image;
use App\Http\Api\Responses\ResponseBodies\Entity\Track as TrackEntity;
use App\Repositories\AlbumRepositoryInterface;
use App\Repositories\ArtistRepositoryInterface;
use App\Repositories\TrackRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Illuminate\Database\Eloquent\Collection;

class UpdateTracksListener
{
    private EntityManager $entityManager;

    private TrackRepositoryInterface $trackRepository;

    private TrackFactoryInterface $trackFactory;

    private ArtistRepositoryInterface $artistRepository;

    private ArtistFactoryInterface $artistFactory;

    private AlbumRepositoryInterface $albumRepository;

    private AlbumFactoryInterface $albumFactory;

    public function __construct(
        EntityManager $entityManager,
        TrackRepositoryInterface $trackRepository,
        TrackFactoryInterface $trackFactory,
        ArtistRepositoryInterface $artistRepository,
        ArtistFactoryInterface $artistFactory,
        AlbumRepositoryInterface $albumRepository,
        AlbumFactoryInterface $albumFactory
    ) {
        $this->entityManager = $entityManager;
        $this->trackRepository = $trackRepository;
        $this->trackFactory = $trackFactory;
        $this->artistRepository = $artistRepository;
        $this->artistFactory = $artistFactory;
        $this->albumRepository = $albumRepository;
        $this->albumFactory = $albumFactory;
    }

    public function handle(UpdateTracksEvent $event): void
    {
        $tracks = $event->getTracks();

        /** @var TrackEntity $track */
        foreach ($tracks as $track) {
            if ($track->getId() === null) {
                continue;
            }

            $model = $this->getTrack($track->getId());

            $model->setPopularity($track->getPopularity());
            $model->setExplicit($track->getExplicit());
            $model->setName($track->getName());
            $model->setType($track->getType());
            $model->setAvailableMarkets($track->getAvailableMarkets());
            $model->setDiscNumber($track->getDiscNumber());
            $model->setDurationms($track->getDurationMs());
            $model->setExternalids($track->getExternalId() ? $track->getExternalId()->toArray() : []);
            $model->setExternalUrls($track->getExternalUrl() ? $track->getExternalUrl()->toArray() : []);
            $model->setHref($track->getHref());
            $model->setLocal($track->getIsLocal());
            $model->setPlayable($track->getIsPlayable());
            $model->setPreviewUrl($track->getPreviewUrl());
            $model->setTrackNumber($track->getTrackNumber());
            $model->setUri($track->getUri());

            $this->setArtists($model, $track->getArtists());

            $this->setAlbum($model, $track->getAlbum());

            $this->entityManager->persist($model);
        }

        $this->entityManager->flush();
    }

    private function getTrack(string $id): TrackInterface
    {
        $track = $this->trackRepository->find($id);

        if ($track === null) {
            /** @var TrackInterface $track */
            $track = $this->trackFactory->createNew();

            $track->setId($id);
        }

        return $track;
    }

    private function setArtists(TrackInterface $track, Collection $artists): void
    {
        $artistIds = $artists->map(fn(ArtistEntity $a) => $a->getId())->toArray();

        /** @var ArtistInterface $artist */
        foreach ($track->getArtists() as $artist) {
            if (!in_array($artist->getId(), $artistIds)) {
                $track->removeArtist($artist);
            }
        }

        /** @var ArtistEntity $artist */
        foreach ($artists as $artist) {
            $model = $this->getArtist($artist);

            $track->addArtist($model);
        }
    }

    private function getArtist(ArtistEntity $artistEntity): ArtistInterface
    {
        /** @var ArtistInterface $artist */
        $artist = $this->artistRepository->find($artistEntity->getId());

        if (!$artist instanceof ArtistInterface) {
            /** @var ArtistInterface $artist */
            $artist = $this->artistFactory->createNew();

            $artist->setId($artistEntity->getId());
        }

        $artist->setFollowers($artistEntity->getFollowers() ? $artistEntity->getFollowers()->toArray() : []);
        $artist->setGenres($artistEntity->getGenres());
        $artist->setHref($artistEntity->getHref());
        $artist->setImages($artistEntity->getImages()->map(fn(Image $i) => $i->toArray())->toArray());
        $artist->setName($artistEntity->getName());
        $artist->setPopularity($artistEntity->getPopularity());
        $artist->setType($artistEntity->getType());
        $artist->setUri($artistEntity->getUri());

        $this->entityManager->persist($artist);

        return $artist;
    }

    private function setAlbum(TrackInterface $track, AlbumEntity $album): void
    {
        $model = $this->getAlbum($album);

        $track->setAlbum($model);
    }

    private function getAlbum(AlbumEntity $albumEntity): AlbumInterface
    {
        /** @var AlbumInterface $album */
        $album = $this->albumRepository->find($albumEntity->getId());

        if ($album === null) {
            /** @var AlbumInterface $album */
            $album = $this->albumFactory->createNew();

            $album->setId($albumEntity->getId());
        }

        $album->setAvailableMarkets($albumEntity->getAvailableMarkets());
        $album->setCopyrights($albumEntity->getCopyrights()->map(fn(Copyright $c) => $c->toArray())->toArray());
        $album->setExternalids($albumEntity->getExternalId() ? $albumEntity->getExternalId()->toArray() : []);
        $album->setExternalUrls($albumEntity->getExternalUrl() ? $albumEntity->getExternalUrl()->toArray() : []);
        $album->setGenres($albumEntity->getGenres());
        $album->setHref($albumEntity->getHref());
        $album->setImages($albumEntity->getImages()->map(fn(Image $i) => $i->toArray())->toArray());
        $album->setLabel($albumEntity->getLabel());
        $album->setName($albumEntity->getName());
        $album->setPopularity($albumEntity->getPopularity());
        $album->setReleaseDate($albumEntity->getReleaseDate());
        $album->setReleaseDatePrecision($albumEntity->getReleaseDatePrecision());
        $album->setRestrictions($albumEntity->getAlbumRestriction() ? $albumEntity->getAlbumRestriction()->toArray() : []);
        $album->setTotalTracks($albumEntity->getTotalTracks());
        $album->setType($albumEntity->getType());
        $album->setUri($albumEntity->getUri());

        $this->entityManager->persist($album);

        $this->setAlbumArtists($album, $albumEntity->getArtists());

        return $album;
    }

    private function setAlbumArtists(AlbumInterface $album, Collection $artists): void
    {
        $artistIds = $artists->map(fn(ArtistEntity $a) => $a->getId())->toArray();

        /** @var ArtistInterface $albumArtist */
        foreach ($album->getArtists() as $artist) {
            if (!in_array($artist->getId(), $artistIds)) {
                $album->removeArtist($artist);
            }
        }

        /** @var ArtistEntity $artist */
        foreach ($artists as $artist) {
            $artist = $this->getArtist($artist);

            $album->addArtist($artist);
        }

        $this->entityManager->persist($album);
    }
}

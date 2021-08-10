<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Http\Api\Events\UpdateTracksEvent;
use App\Http\Api\Responses\ResponseBodies\Entity\Album as AlbumEntity;
use App\Http\Api\Responses\ResponseBodies\Entity\Artist as ArtistEntity;
use App\Http\Api\Responses\ResponseBodies\Entity\Copyright;
use App\Http\Api\Responses\ResponseBodies\Entity\Image;
use App\Models\Spotify\Album;
use App\Models\Spotify\AlbumArtist;
use App\Models\Spotify\Artist;
use App\Models\Spotify\Track;
use App\Http\Api\Responses\ResponseBodies\Entity\Track as TrackEntity;
use App\Models\Spotify\TrackArtist;
use Illuminate\Database\Eloquent\Collection;

class UpdateTracksListener
{
    public function handle(UpdateTracksEvent $event): void
    {
        $tracks = $event->getTracks();

        /** @var TrackEntity $track */
        foreach ($tracks as $track) {
            if ($track->getId() === null) {
                continue;
            }

            $model = $this->getTrack($track->getId());

            $model->popularity = $track->getPopularity();
            $model->explicit = $track->getExplicit();
            $model->name = $track->getName();
            $model->type = $track->getType();
            $model->available_markets = $track->getAvailableMarkets();
            $model->disc_number = $track->getDiscNumber();
            $model->duration_ms = $track->getDurationMs();
            $model->external_ids = $track->getExternalId() ? $track->getExternalId()->toArray() : [];
            $model->external_urls = $track->getExternalUrl() ? $track->getExternalUrl()->toArray() : [];
            $model->href = $track->getHref();
            $model->is_local = $track->getIsLocal();
            $model->is_playable = $track->getIsPlayable();
            $model->preview_url = $track->getPreviewUrl();
            $model->track_number = $track->getTrackNumber();
            $model->uri = $track->getUri();

            $model->save();

            $this->setArtists($model, $track->getArtists());

            $this->setAlbum($model, $track->getAlbum());
        }
    }

    private function getTrack(string $id): Track
    {
        $track = Track::find($id);

        if ($track === null) {
            $track = new Track();

            $track->id = $id;
        }

        return $track;
    }

    private function setArtists(Track $track, Collection $artists): void
    {
        $artistIds = $artists->map(fn(ArtistEntity $a) => $a->getId())->toArray();

        /** @var TrackArtist $artist */
        foreach ($track->trackArtists as $trackArtist) {
            if (!in_array($trackArtist->artist_id, $artistIds)) {
                $this->getTrackArtist($track->id, $trackArtist->artist_id)->delete();
            }
        }

        /** @var ArtistEntity $artist */
        foreach ($artists as $artist) {
            $model = $this->getArtist($artist);

            $this->getTrackArtist($track->id, $model->id)->save();
        }
    }

    private function getArtist(ArtistEntity $artistEntity): Artist
    {
        $artist = Artist::find($artistEntity->getId());

        if ($artist === null) {
            $artist = new Artist();

            $artist->id = $artistEntity->getId();
        }

        $artist->followers = $artistEntity->getFollowers() ? $artistEntity->getFollowers()->toArray() : [];
        $artist->genres = $artistEntity->getGenres();
        $artist->href = $artistEntity->getHref();
        $artist->images = $artistEntity->getImages()->map(fn(Image $i) => $i->toArray())->toArray();
        $artist->name = $artistEntity->getName();
        $artist->popularity = $artistEntity->getPopularity();
        $artist->type = $artistEntity->getType();
        $artist->uri = $artistEntity->getUri();

        $artist->save();

        return $artist;
    }

    private function setAlbum(Track $track, AlbumEntity $album): void
    {
        $model = $this->getAlbum($album);

        $track->album_id = $model->id;

        $track->save();
    }

    private function getAlbum(AlbumEntity $albumEntity): Album
    {
        $album = Album::find($albumEntity->getId());

        if ($album === null) {
            $album = new Album();

            $album->id = $albumEntity->getId();
        }

        $album->available_markets = $albumEntity->getAvailableMarkets();
        $album->copyrights = $albumEntity->getCopyrights()->map(fn(Copyright $c) => $c->toArray())->toArray();
        $album->external_ids = $albumEntity->getExternalId() ? $albumEntity->getExternalId()->toArray() : [];
        $album->external_urls = $albumEntity->getExternalUrl() ? $albumEntity->getExternalUrl()->toArray() : [];
        $album->genres = $albumEntity->getGenres();
        $album->href = $albumEntity->getHref();
        $album->images = $albumEntity->getImages()->map(fn(Image $i) => $i->toArray())->toArray();
        $album->label = $albumEntity->getLabel();
        $album->name = $albumEntity->getName();
        $album->popularity = $albumEntity->getPopularity();
        $album->release_date = $albumEntity->getReleaseDate();
        $album->release_date_precision = $albumEntity->getReleaseDatePrecision();
        $album->restrictions = $albumEntity->getAlbumRestriction() ? $albumEntity->getAlbumRestriction()->toArray() : [];
        $album->total_tracks = $albumEntity->getTotalTracks();
        $album->type = $albumEntity->getType();
        $album->uri = $albumEntity->getUri();

        $album->save();

        $this->setAlbumArtists($album, $albumEntity->getArtists());

        return $album;
    }

    private function setAlbumArtists(Album $album, Collection $artists): void
    {
        $artistIds = $artists->map(fn(ArtistEntity $a) => $a->getId())->toArray();

        /** @var AlbumArtist $albumArtist */
        foreach ($album->albumArtists as $albumArtist) {
            if (!in_array($albumArtist->artist_id, $artistIds)) {
                $this->getAlbumArtist($album->id, $albumArtist->artist_id)->delete();
            }
        }

        /** @var ArtistEntity $artist */
        foreach ($artists as $artist) {
            $artist = $this->getArtist($artist);

            $this->getAlbumArtist($album->id, $artist->id)->save();
        }
    }

    private function getTrackArtist(string $trackId, string $artistId): TrackArtist
    {
        $trackArtist = TrackArtist::where('track_id', $trackId)->where('artist_id', $artistId)->first();

        if ($trackArtist === null) {
            $trackArtist = new TrackArtist();

            $trackArtist->track_id = $trackId;
            $trackArtist->artist_id = $artistId;
        }

        return $trackArtist;
    }

    private function getAlbumArtist(string $albumId, string $artistId): AlbumArtist
    {
        $albumArtist = AlbumArtist::where('album_id', $albumId)->where('artist_id', $artistId)->first();

        if ($albumArtist === null) {
            $albumArtist = new AlbumArtist();

            $albumArtist->album_id = $albumId;
            $albumArtist->artist_id = $artistId;
        }

        return $albumArtist;
    }
}

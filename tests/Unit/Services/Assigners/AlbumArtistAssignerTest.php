<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Assigners;

use App\Entities\Spotify\Album;
use App\Entities\Spotify\AlbumInterface;
use App\Http\Api\Responses\ResponseBodies\Entity\Artist;
use App\Services\Assigners\AlbumArtistAssigner;
use App\Services\Assigners\AlbumArtistAssignerInterface;
use App\Services\Providers\Spotify\ArtistProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Tests\TestCase;

class AlbumArtistAssignerTest extends TestCase
{
    private function getAssigner(): AlbumArtistAssignerInterface
    {
        $albumProvider = $this->getMockBuilder(ArtistProvider::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['provide'])
            ->getMock();

        $albumProvider->expects($this->any())
            ->method('provide')
            ->will($this->returnCallback(
                static function (Artist $artist) {
                    $artistEntity = new \App\Entities\Spotify\Artist();

                    $artistEntity->setName($artist->getName());

                    return $artistEntity;
                }
            ));

        return new AlbumArtistAssigner(
            $albumProvider
        );
    }

    private function getAlbum(array $artists): AlbumInterface
    {
        $album = new Album();

        foreach ($artists as $artistName) {
            $artist = new \App\Entities\Spotify\Artist();

            $artist->setName($artistName);

            $album->addArtist($artist);
        }

        return $album;
    }

    private function getArtistCollection(array $artists): Collection
    {
        $artistCollection = new ArrayCollection();

        foreach ($artists as $artistName) {
            $artist = new Artist();

            $artist->setName($artistName);

            $artistCollection->add($artist);
        }

        return $artistCollection;
    }

    /** @dataProvider testAssignDataProvider */
    public function testAssign(array $originalArtists, array $newArtists): void
    {
        $assigner = $this->getAssigner();

        $assigner->assign($album = $this->getAlbum($originalArtists), $this->getArtistCollection($newArtists));

        $albumArtistNames = $album->getArtists()->map(static fn ($artist) => $artist->getName());

        $this->assertEquals($albumArtistNames, $newArtists);
    }

    private function testAssignDataProvider(): array
    {
        return [
            [[], ['test', 'test_2']],
        ];
    }
}
<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Api\Factories\RequestBodies;

use App\Http\Api\Factories\RequestBodies\AddTrackToPlaylistRequestBodyFactory;
use Tests\TestCase;

class AddTrackToPlaylistRequestBodyFactoryTest extends TestCase
{
    private function getFactory(): AddTrackToPlaylistRequestBodyFactory
    {
        return new AddTrackToPlaylistRequestBodyFactory();
    }

    /** @dataProvider uriDataProvider */
    public function testCreate(?array $uris, ?int $position = null): void
    {
        $requestBody = $this->getFactory()->create($uris, $position);

        $this->assertEquals($uris, $requestBody->getUris());
        $this->assertEquals($position, $requestBody->getPosition());
    }

    private function uriDataProvider(): array
    {
        return [
            'emptyUriArray' => [null],
            'singleUri' => [['uri_1']],
            'multipleUrisWithPosition' => [['uri_1', 'uri_2'], 1],
        ];
    }
}
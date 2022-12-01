<?php

declare(strict_types=1);

namespace Tests\Unit\Services\User;

use App\Entities\ScopeInterface;
use App\Entities\User;
use App\Entities\UserInterface;
use App\Http\Api\Authentication\Responses\Entity\Scope;
use App\Http\Api\Authentication\Responses\RefreshedAccessTokenResponse;
use App\Http\Api\Authentication\SpotifyAuthenticationApi;
use App\Repositories\ScopeRepository;
use App\Services\User\SpotifyReauthenticationService;
use App\Services\User\SpotifyReauthenticationServiceInterface;
use Doctrine\ORM\EntityManager;
use Tests\TestCase;

class SpotifyReauthenticationServiceTest extends TestCase
{
    private function getReauthenticationService(array $scopes, bool $returnScopes = true): SpotifyReauthenticationServiceInterface
    {
        $spotifyAuthenticationApi = $this->getMockBuilder(SpotifyAuthenticationApi::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['refreshAccessToken'])
            ->getMock();

        $spotifyAuthenticationApi->expects($this->once())
            ->method('refreshAccessToken')
            ->will(
                $this->returnValue($this->getAccessTokenResponse($scopes))
            );

        $scopeRepository = $this->getMockBuilder(ScopeRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['findOneByName'])
            ->getMock();

        if (!$returnScopes) {
            $scopeRepository->expects($this->any())
                ->method('findOneByName')
                ->will(
                    $this->returnValue(null)
                );
        } else {
            $scopeRepository->expects($this->any())
                ->method('findOneByName')
                ->will(
                    $this->returnCallback(
                        static function ($argument) {
                            $scope = new \App\Entities\Scope();

                            $scope->setName($argument);

                            return $scope;
                        }
                    )
                );
        }

        return new SpotifyReauthenticationService(
            $spotifyAuthenticationApi,
            $scopeRepository,
            $this->createMock(EntityManager::class)
        );
    }

    private function getAccessTokenResponse(array $scopes): RefreshedAccessTokenResponse
    {
        $response = new RefreshedAccessTokenResponse();

        $scope = new Scope();

        foreach ($scopes as $scopeName) {
            $scope->addScope($scopeName);
        }

        $response->setAccessToken('access_token');
        $response->setExpiresIn(60);
        $response->setScope($scope);

        return $response;
    }

    private function getUser(): UserInterface
    {
        $user = new User();

        $user->setSpotifyRefreshToken('refresh_token');
        $user->setSpotifyAccessToken('old_access_token');

        return $user;
    }

    /** @dataProvider scopeDataProvider */
    public function testReauthenticate(array $scopes): void
    {
        $user = $this->getUser();

        $reauthenticationService = $this->getReauthenticationService($scopes);

        $reauthenticationService->reauthenticate($user);

        $this->assertEquals('access_token', $user->getSpotifyAccessToken());

        /** @var ScopeInterface $scope */
        foreach ($user->getScopes() as $scope) {
            $this->assertContains($scope->getName(), $scopes);
        }

        if (empty($scopes)) {
            $this->assertEquals(0, $user->getScopes()->count());
        }
    }

    public function testScopeNotFound(): void
    {
        $user = $this->getUser();

        $reauthenticationService = $this->getReauthenticationService(['undefined_scope'], false);

        $this->expectException(\LogicException::class);

        $reauthenticationService->reauthenticate($user);
    }

    private function scopeDataProvider(): array
    {
        return [
            [[]],
            [['test_scope_1', 'test_scope_2']],
        ];
    }
}
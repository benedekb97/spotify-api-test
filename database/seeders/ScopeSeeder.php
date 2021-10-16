<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use Illuminate\Database\Seeder;

// TODO: Use actual factory, not shit eloquent create method (the class has been deleted anyways lol)
class ScopeSeeder extends Seeder
{
    public function run(): void
    {
        foreach (SpotifyAuthenticationApiInterface::SCOPE_ENABLED_MAP as $scope => $enabled) {
            Scope::create(
                [
                    'name' => $scope
                ]
            );
        }
    }
}

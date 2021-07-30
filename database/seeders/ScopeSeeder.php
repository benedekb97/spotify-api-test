<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Models\Scope;
use Illuminate\Database\Seeder;

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

<?php

declare(strict_types=1);

namespace App\Http\Api\Authentication\Requests;

use App\Http\Requests\Api\ApiRequest;

class AccessTokenRequest extends ApiRequest
{
    public string $grantType;

    public string $code;

    public string $redirectUri;

    public string $clientId;

    public string $clientSecret;
}

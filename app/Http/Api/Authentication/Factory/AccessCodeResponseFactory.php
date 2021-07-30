<?php

declare(strict_types=1);

namespace App\Http\Api\Authentication\Factory;

use App\Http\Api\Authentication\Responses\AccessCodeResponse;
use Illuminate\Http\Request;

class AccessCodeResponseFactory
{
    public function create(Request $request): AccessCodeResponse
    {
        $object = new AccessCodeResponse();

        $object->setCode($request->get('code'));
        $object->setError($request->get('error'));
        $object->setState($request->get('state'));

        return $object;
    }
}

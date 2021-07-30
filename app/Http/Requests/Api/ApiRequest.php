<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Support\Str;
use ReflectionClass;
use Serializable;

class ApiRequest implements Serializable
{
    public function serialize(): array
    {
        $reflectionClass = new ReflectionClass($this);

        $serialized = [];

        foreach ($reflectionClass->getProperties() as $property) {
            $key = Str::snake($property->getName());

            $serialized[$key] = $property->getValue();
        }

        return $serialized;
    }

    /**
     * @param array $data
     */
    public function unserialize($data): self
    {
        $object = new self();

        foreach ($data as $key => $value) {
            $propertyName = Str::camel($key);

            $object->{$propertyName} = $value;
        }

        return $object;
    }
}

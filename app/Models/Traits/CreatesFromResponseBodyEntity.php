<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Http\Api\Responses\ResponseBodies\Entity\EntityInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use ReflectionClass;

trait CreatesFromResponseBodyEntity
{
    public static function createOrUpdate(Model $model, EntityInterface $entity): void
    {
        $reflectionClass = new ReflectionClass($entity);

        $data = [];

        foreach ($reflectionClass->getProperties() as $property) {
            $data[Str::snake($property->getName())] = $property->getValue();
        }

        $model::updateOrCreate($data);
    }
}

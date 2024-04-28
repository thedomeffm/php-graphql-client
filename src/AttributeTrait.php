<?php

namespace TheDomeFfm\GraphQlClient;

use TheDomeFfm\GraphQlClient\Attribute\Api;
use TheDomeFfm\GraphQlClient\Attribute\Property;
use TheDomeFfm\GraphQlClient\Exception\MissingApiAttributeException;

trait AttributeTrait
{
    private function getApiAttribute(ApiObjectInterface $apiObject): Api
    {
        $reflectionClass = new \ReflectionClass($apiObject);
        $attributes = $reflectionClass->getAttributes(Api::class);

        if (count($attributes) !== 1) {
            throw new MissingApiAttributeException($apiObject);
        }

        return $attributes[0]->newInstance();
    }

    private function getPropertyAttribute(\ReflectionProperty $property): ?Property
    {
        $attributes = $property->getAttributes(Property::class);

        if (count($attributes) !== 1) {
            return null;
        }

        return $attributes[0]->newInstance();
    }
}
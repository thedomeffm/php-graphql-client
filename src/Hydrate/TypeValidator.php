<?php

namespace TheDomeFfm\GraphQlClient\Hydrate;

class TypeValidator
{
    public function isPrimitiveProperty(\ReflectionProperty $reflectionProperty): bool
    {
        if ($reflectionProperty->getType() === null) {
            return true;
        }

        return $reflectionProperty->getType()->getName();
    }

    public function isCompatible(\ReflectionProperty $reflectionProperty, mixed $value): bool
    {
        if ($reflectionProperty->getType() === null) {
            return true;
        }

        if ($reflectionProperty->getType()->getName() === gettype($value)) {
            return true;
        }

        return false;
    }
}
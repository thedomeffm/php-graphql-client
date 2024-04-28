<?php

namespace TheDomeFfm\GraphQlClient\Hydrate;

use Psr\Http\Message\ResponseInterface;
use TheDomeFfm\GraphQlClient\ApiObjectInterface;
use TheDomeFfm\GraphQlClient\Attribute\Api;
use TheDomeFfm\GraphQlClient\Attribute\Property;
use TheDomeFfm\GraphQlClient\AttributeTrait;

class ApiObjectHydrator implements ApiObjectHydratorInterface
{
    use AttributeTrait;

    private readonly TypeValidator $typeValidator;

    public function __construct() {
        // TODO: add missing validation before setting the values
        $this->typeValidator = new TypeValidator();
    }

    public function hydrate(ApiObjectInterface $apiObject, ResponseInterface $response): ApiObjectInterface
    {
        $apiAttribute = $this->getApiAttribute($apiObject);

        $json = (string) $response->getBody();
        if (!json_validate($json)) {
            throw new \RuntimeException(json_last_error_msg());
        }
        $data = json_decode($json, true, 512, JSON_BIGINT_AS_STRING | JSON_THROW_ON_ERROR);

        if (!$this->hasClassWithHydrateableProperties($apiObject::class)) {
            throw new \RuntimeException(sprintf('The class \'%s\' has no hydrateable properties!', $apiObject::class));
        }

        $this->hydrateProperties(
            parentObject: $apiObject,
            properties: $this->getHydrateableProperties($apiObject),
            apiAttribute: $apiAttribute,
            data: $data,
        );

        return $apiObject;
    }

    /**
     * @return array<\ReflectionProperty>
     */
    private function getHydrateableProperties(string|object $object): array
    {
        $hydrateableProperties = [];

        foreach ((new \ReflectionClass($object))->getProperties() as $reflectionProperty) {
            $propertyAttribute = $this->getPropertyAttribute($reflectionProperty);
            if ($propertyAttribute) {
                $hydrateableProperties[] = $reflectionProperty;
            }
        }

        return $hydrateableProperties;
    }

    private function hasClassWithHydrateableProperties(string $class): bool
    {
        foreach ((new \ReflectionClass($class))->getProperties() as $reflectionProperty) {
            $propertyAttribute = $this->getPropertyAttribute($reflectionProperty);
            if ($propertyAttribute) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<\ReflectionProperty> $properties
     */
    private function hydrateProperties(
        object $parentObject,
        array $properties,
        Api $apiAttribute,
        array $data,
    ): void {
        $parentReflection = new \ReflectionClass($parentObject);
        foreach ($properties as $property) {
            $attribute = $this->getPropertyAttribute($property);
            if (!$attribute) {
                throw new \RuntimeException(sprintf('All properties must have the \'%s\' attribute', Property::class));
            }
            if (!$parentReflection->hasProperty($property->getName())) {
                throw new \RuntimeException(sprintf('Object %s does not have a Property \'%s\'', $parentObject::class, $property->getName()));
            }

            $propertyPath = ltrim($apiAttribute->path, '.') . '.' . $attribute->path;
            $value = $this->get($data, $propertyPath);

            if ($property->getType() === null) {
                $property->setValue($parentObject, $value);

                continue;
            }

            if ($property->getType() instanceof \ReflectionUnionType) {
                throw new \RuntimeException('Union types are not supported!');
            }

            if ($property->getType() instanceof \ReflectionIntersectionType) {
                throw new \RuntimeException('Intersection types are not supported!');
            }

            if ($property->getType()->isBuiltin()) {
                $property->setValue($parentObject, $value);

                continue;
            }

            if ($property->getType()->getName() === 'DateTime') {
                if (is_string($value)) {
                    $property->setValue($parentObject, new \DateTime($value));
                } else if (is_int($value)) {
                    $property->setValue($parentObject, (new \DateTime())->setTimestamp($value));
                } else if ($property->getType()->allowsNull()) {
                    $property->setValue($parentObject, null);
                } else {
                    throw new \RuntimeException('can not ');
                }

                continue;
            }

            if ($property->getType()->getName() === 'DateTimeImmutable'
                || $property->getType()->getName() === 'DateTimeInterface'
            ) {
                if (is_string($value)) {
                    $property->setValue($parentObject, new \DateTimeImmutable($value));
                } else if (is_int($value)) {
                    $property->setValue($parentObject, (new \DateTimeImmutable())->setTimestamp($value));
                } else if ($property->getType()->allowsNull()) {
                    $property->setValue($parentObject, null);
                } else {
                    throw new \RuntimeException('can not ');
                }

                continue;
            }

            // Class hydration:
            if (!$this->hasClassWithHydrateableProperties($property->getType()->getName())) {
                throw new \RuntimeException(sprintf('The class \'%s\' has no hydrateable properties!', $property->getType()->getName()));
            }

            $childObject = (new \ReflectionClass($property->getType()->getName()))->newInstanceWithoutConstructor();
            $this->hydrateProperties(
                parentObject: $childObject,
                properties: $this->getHydrateableProperties($property->getType()->getName()),
                apiAttribute: new Api(path: ltrim($apiAttribute->path, '.') . '.' . $attribute->path),
                data: $data,
            );
            $property->setValue($parentObject, $childObject);
        }
    }



    private function get(array $data, string $path, $defaultValue = null) {
        $keys = explode('.', $path);

        foreach ($keys as $key) {
            if (is_array($data) && array_key_exists($key, $data)) {
                $data = $data[$key];
            } else {
                return $defaultValue;
            }
        }

        return $data;
    }
}
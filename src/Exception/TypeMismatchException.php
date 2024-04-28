<?php

namespace TheDomeFfm\GraphQlClient\Exception;

use TheDomeFfm\GraphQlClient\ApiObjectInterface;
use TheDomeFfm\GraphQlClient\AttributeTrait;

class TypeMismatchException extends GraphQlClientException
{
    use AttributeTrait;

    public function __construct(
        ApiObjectInterface $apiObject,
        \ReflectionProperty $reflectionProperty,
        mixed $unmatchedValue,
    ) {
        parent::__construct(sprintf('The property \'%s\' (property path: \'%s\') of ApiObject \'%s\' does not match the typed property \'%s\' - given type from response is of type \'%s\'. Validate that your ApiInput queries for this field.', $reflectionProperty->getName(), $this->getPropertyAttribute($reflectionProperty)->path, $apiObject::class, $reflectionProperty->getType()?->getName() ?? 'unknown', gettype($unmatchedValue)));
    }
}
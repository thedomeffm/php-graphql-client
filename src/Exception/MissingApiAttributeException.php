<?php

namespace TheDomeFfm\GraphQlClient\Exception;

use TheDomeFfm\GraphQlClient\ApiObjectInterface;
use TheDomeFfm\GraphQlClient\Attribute\Api;

class MissingApiAttributeException extends GraphQlClientException
{
    public function __construct(
        ApiObjectInterface $apiObject,
    ) {
        parent::__construct(sprintf('The ApiObject of class %s does not have an %s Attribute!', $apiObject::class, Api::class));
    }
}
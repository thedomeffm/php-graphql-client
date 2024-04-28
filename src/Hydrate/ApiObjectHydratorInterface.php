<?php

namespace TheDomeFfm\GraphQlClient\Hydrate;

use Psr\Http\Message\ResponseInterface;
use TheDomeFfm\GraphQlClient\ApiObjectInterface;

interface ApiObjectHydratorInterface
{
    public function hydrate(ApiObjectInterface $apiObject, ResponseInterface $response): ApiObjectInterface;
}
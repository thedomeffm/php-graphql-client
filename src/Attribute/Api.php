<?php

namespace TheDomeFfm\GraphQlClient\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
readonly class Api
{
    public function __construct(
        public string $uri = '',
        public string $path = '',
        public string $method = 'POST',
    ) {}
}

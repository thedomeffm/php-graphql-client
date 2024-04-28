<?php

namespace TheDomeFfm\GraphQlClient\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Property
{
    public function __construct(
        public readonly string $path,
    ) {}
}
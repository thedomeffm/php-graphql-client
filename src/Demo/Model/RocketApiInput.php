<?php

namespace TheDomeFfm\GraphQlClient\Demo\Model;

use TheDomeFfm\GraphQlClient\ApiInputInterface;

class RocketApiInput implements ApiInputInterface
{
    public function __construct(public readonly string $id) {}

    public function getQuery(): string
    {
        return <<<'GraphQl'
        query getRocket($ID: ID!) {
            rocket(id: $ID) {
                id
                active
                name
                first_flight
                cost_per_launch
            }
        }
        GraphQl;
    }

    public function getVariables(): array
    {
        return ['ID' => $this->id];
    }
}
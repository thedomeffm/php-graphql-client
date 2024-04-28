<?php

namespace TheDomeFfm\GraphQlClient\Demo\Model;

use TheDomeFfm\GraphQlClient\ApiInputInterface;

class LaunchApiInput implements ApiInputInterface
{
    public function __construct(public readonly string $id) {}

    public function getQuery(): string
    {
        return <<<'GraphQl'
        query getLaunch($ID: ID!) {
            launch(id: $ID) {
                id
                mission_id
                mission_name
                rocket {
                    rocket_name
                    rocket {
                        id
                        active
                        name
                        first_flight
                        cost_per_launch
                    }
                }
            }
        }
        GraphQl;
    }

    public function getVariables(): array
    {
        return ['ID' => $this->id];
    }
}
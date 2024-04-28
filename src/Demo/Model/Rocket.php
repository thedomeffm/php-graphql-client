<?php

namespace TheDomeFfm\GraphQlClient\Demo\Model;

use TheDomeFfm\GraphQlClient\ApiInputInterface;
use TheDomeFfm\GraphQlClient\ApiObjectInterface;
use TheDomeFfm\GraphQlClient\Attribute\Api;
use TheDomeFfm\GraphQlClient\Attribute\Property;

#[Api(
    uri: 'https://spacex-production.up.railway.app',
    path: 'data.rocket',
)]
class Rocket implements ApiObjectInterface
{
    private RocketApiInput $apiInput;

    #[Property('id')]
    public string $id;
    #[Property('active')]
    public bool $active;
    #[Property('name')]
    public string $name;
    #[Property('first_flight')]
    public \DateTime $firstFlight;
    #[Property('cost_per_launch')]
    public int $costPerLaunch;

    public function __construct(string $id)
    {
        $this->apiInput = new RocketApiInput($id);
    }

    public function getApiInput(): ApiInputInterface
    {
        return $this->apiInput;
    }
}
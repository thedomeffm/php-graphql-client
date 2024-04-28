<?php

namespace TheDomeFfm\GraphQlClient\Demo\Model;

use TheDomeFfm\GraphQlClient\ApiInputInterface;
use TheDomeFfm\GraphQlClient\ApiObjectInterface;
use TheDomeFfm\GraphQlClient\Attribute\Api;
use TheDomeFfm\GraphQlClient\Attribute\Property;

#[Api(
    uri: 'https://spacex-production.up.railway.app',
    path: 'data.launch',
)]
class Launch implements ApiObjectInterface
{
    private LaunchApiInput $apiInput;

    #[Property('id')]
    public string $id;
    #[Property('mission_id.0')]
    public ?string $missionId;
    #[Property('mission_name')]
    public string $missionName;
    #[Property('rocket.rocket')]
    public Rocket $rocket;
    #[Property('example.that-does-not-exist')]
    public $speedParameter;

    public function __construct(string $id)
    {
        $this->apiInput = new LaunchApiInput($id);
    }

    public function getApiInput(): ApiInputInterface
    {
        return $this->apiInput;
    }
}
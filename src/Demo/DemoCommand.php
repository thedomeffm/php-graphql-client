<?php

namespace TheDomeFfm\GraphQlClient\Demo;

use TheDomeFfm\GraphQlClient\Demo\Model\Launch;
use TheDomeFfm\GraphQlClient\Demo\Model\Rocket;
use TheDomeFfm\GraphQlClient\GraphQlClientFactory;

class DemoCommand
{
    public function run()
    {
        $client = GraphQlClientFactory::create();

        dump('Launch Information:');
        $launch = new Launch(id: '5eb87ce1ffd86e000604b333');
        $client->fetch($launch);
        dump($launch);

        dump('Rocket Information:');
        $rocket = new Rocket(id: '5e9d0d95eda69973a809d1ec');
        $client->fetch($rocket);
        dump($rocket);
    }
}

include __DIR__ . '/../../vendor/autoload.php';

(new DemoCommand())->run();

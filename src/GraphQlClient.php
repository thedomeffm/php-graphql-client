<?php

namespace TheDomeFfm\GraphQlClient;

use Http\Discovery\Psr18ClientDiscovery;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use TheDomeFfm\GraphQlClient\Hydrate\ApiObjectHydrator;

class GraphQlClient implements GraphQlClientInterface
{
    use AttributeTrait;

    private readonly ClientInterface $httpClient;

    public function __construct(
        ?ClientInterface $httpClient = null,
        private readonly ApiObjectHydrator $apiObjectHydrator = new ApiObjectHydrator(),
    ) {
        $this->httpClient = $httpClient ?: Psr18ClientDiscovery::find();
    }

    public function fetch(ApiObjectInterface $apiObject): ApiObjectInterface
    {
        $apiAttribute = $this->getApiAttribute($apiObject);

        /** @var RequestInterface $request */
        $request = $this->httpClient->createRequest($apiAttribute->method, $apiAttribute->uri);

        $requestBody = json_encode(
            [
                'query' => $apiObject->getApiInput()->getQuery(),
                'variables' => $apiObject->getApiInput()->getVariables(),
            ],
            JSON_THROW_ON_ERROR,
        );
        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withBody((new Psr17Factory())->createStream($requestBody));

        $response = $this->httpClient->sendRequest($request);

        $this->apiObjectHydrator->hydrate($apiObject, $response);

        return $apiObject;
    }
}
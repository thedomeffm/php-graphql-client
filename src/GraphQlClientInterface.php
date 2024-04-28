<?php

namespace TheDomeFfm\GraphQlClient;

interface GraphQlClientInterface
{
    public function fetch(ApiObjectInterface $apiObject): ?ApiObjectInterface;
}

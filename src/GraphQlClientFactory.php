<?php

namespace TheDomeFfm\GraphQlClient;

class GraphQlClientFactory {
    public static function create(): GraphQlClient
    {
        return new GraphQlClient();
    }
}
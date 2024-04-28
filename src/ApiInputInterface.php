<?php

namespace TheDomeFfm\GraphQlClient;

interface ApiInputInterface
{
    public function getQuery(): string;

    public function getVariables(): array;
}
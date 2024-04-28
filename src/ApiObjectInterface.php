<?php

namespace TheDomeFfm\GraphQlClient;

interface ApiObjectInterface
{
    public function getApiInput(): ApiInputInterface;
}
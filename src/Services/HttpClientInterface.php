<?php

namespace App\Services;

interface HttpClientInterface
{
    /**
     * Performs a GET request and returns a response array.
     *
     * @param string $url
     * @param array $params
     * @return array
     */
    public function get(string $url, array $params = []): array;

    /**
     * Queries ViaCEP and returns address data.
     *
     * @param string $cep
     * @return array
     */
    public function getAddressByCep(string $cep): array;
} 
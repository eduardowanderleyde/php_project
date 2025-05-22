<?php

namespace App\Services;

interface HttpClientInterface
{
    /**
     * Realiza uma requisição GET e retorna um array de resposta.
     *
     * @param string $url
     * @param array $params
     * @return array
     */
    public function get(string $url, array $params = []): array;
} 
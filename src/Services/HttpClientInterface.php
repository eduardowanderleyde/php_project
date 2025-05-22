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

    /**
     * Consulta o ViaCEP e retorna os dados do endereço.
     *
     * @param string $cep
     * @return array
     */
    public function getAddressByCep(string $cep): array;
} 
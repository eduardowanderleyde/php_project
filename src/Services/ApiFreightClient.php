<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ApiFreightClient implements HttpClientInterface
{
    protected Client $client;
    protected int $timeout;
    protected int $maxRetries;

    public function __construct(int $timeout = 3, int $maxRetries = 2)
    {
        $this->client = new Client();
        $this->timeout = $timeout;
        $this->maxRetries = $maxRetries;
    }

    public function get(string $url, array $params = []): array
    {
        $attempts = 0;
        $query = http_build_query($params);
        $fullUrl = $url . ($query ? '?' . $query : '');
        do {
            try {
                $response = $this->client->request('GET', $fullUrl, [
                    'timeout' => $this->timeout,
                ]);
                $body = $response->getBody()->getContents();
                $data = json_decode($body, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \RuntimeException('Invalid JSON response');
                }
                return $data;
            } catch (GuzzleException $e) {
                $attempts++;
                if ($attempts > $this->maxRetries) {
                    throw new \RuntimeException('API request failed after retries: ' . $e->getMessage());
                }
            }
        } while ($attempts <= $this->maxRetries);
        throw new \RuntimeException('API request failed');
    }

    /**
     * Consulta o ViaCEP e retorna os dados do endereço.
     *
     * @param string $cep
     * @return array
     */
    public function getAddressByCep(string $cep): array
    {
        $cep = preg_replace('/\D/', '', $cep);
        $url = "https://viacep.com.br/ws/{$cep}/json/";
        $data = $this->get($url);
        if (isset($data['erro']) && $data['erro'] === true) {
            throw new \RuntimeException('CEP not found');
        }
        return $data;
    }
} 
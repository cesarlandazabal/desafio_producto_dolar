<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Servicio para obtener los valores del dólar
 */
class DollarService
{
    /** @var HttpClientInterface */
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Retorna los valores del dólar
     *
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getDollarPrices(): array
    {
        $response     = $this->getDollarPricesFromApi();
        $content      = $response->toArray();
        $dollarPrices = [];

        foreach ($content as $item) {
            $dollarPrices[] = [
                'name'  => $item['casa']['nombre'],
                'buy'   => $item['casa']['compra'],
                'sell'  => $item['casa']['venta']
            ];
        }

        return $dollarPrices;
    }

    /**
     * Retorna los valores del dólar desde la API de DolarSi
     *
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     */
    public function getDollarPricesFromApi(): ResponseInterface
    {
        return $this->httpClient->request('GET', 'https://www.dolarsi.com/api/api.php?type=valoresprincipales');
    }
}
<?php

namespace App\Service;


use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherService
{

    private HttpClientInterface $httpClient;
    private LoggerInterface $logger;
    private string $apiKey;

    /**
     * @param HttpClientInterface   $httpClient
     * @param LoggerInterface       $logger
     * @param ParameterBagInterface $params
     */
    public function __construct(HttpClientInterface $httpClient, LoggerInterface $logger, ParameterBagInterface $params)
    {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->apiKey = $params->get('weather_api_key');
    }

    /**
     * @param string $city
     *
     * @return array|string[]
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getWeatherData(string $city): array
    {
        $url = "https://api.weatherapi.com/v1/current.json?key={$this->apiKey}&q={$city}";

        try {
            $response = $this->httpClient->request('GET', $url);
            $data = $response->toArray();
        } catch (Exception $e) {
            $this->logger->error('Weather API request failed: ' . $e->getMessage());
            return ['error' => 'Unable to fetch weather data.'];
        }

        if (isset($data['error'])) {
            return ['error' => $data['error']['message']];
        }

        return [
            'city' => $data['location']['name'],
            'country' => $data['location']['country'],
            'temperature' => $data['current']['temp_c'],
            'condition' => $data['current']['condition']['text'],
            'humidity' => $data['current']['humidity'],
            'wind_speed' => $data['current']['wind_kph'],
            'last_updated' => $data['current']['last_updated'],
        ];
    }



}
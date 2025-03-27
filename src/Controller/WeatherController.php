<?php

namespace App\Controller;

use App\Form\CityType;
use App\Service\WeatherLoggerService;
use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class WeatherController extends AbstractController
{

    private WeatherService $weatherService;
    private WeatherLoggerService $weatherLoggerService;

    public function __construct(
        WeatherService $weatherService,
        WeatherLoggerService $weatherLoggerService
    ) {
        $this->weatherService = $weatherService;
        $this->weatherLoggerService = $weatherLoggerService;
    }


    #[Route('/', name: 'app_weather')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(CityType::class);
        $form->handleRequest($request);
        $weatherData = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $city = $form->get('city')->getData();
            $weatherData = $this->weatherService->getWeatherData($city);
            // write to weather_log.txt
            $this->weatherLoggerService->logWeather($weatherData);
        }

        return $this->render('weather/index.html.twig', [
            'weather' => $weatherData,
            'form'    => $form->createView(),
        ]);
    }
}

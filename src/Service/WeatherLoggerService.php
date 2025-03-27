<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;

class WeatherLoggerService
{
    private string $logFile;
    private LoggerInterface $logger;
    private Filesystem $filesystem;

    public function __construct(
        string $logFile,
        LoggerInterface $logger,
        Filesystem $filesystem
    ) {
        $this->logFile = $logFile;
        $this->logger = $logger;
        $this->filesystem = $filesystem;
    }

    /**
     * @param array $weatherData
     *
     * @return void
     */
    public function logWeather(array $weatherData): void
    {
        if (!isset($weatherData['city'], $weatherData['temperature'], $weatherData['condition'])) {
            $this->logger->error(
                'Недостаточно данных для логирования погоды.',
                $weatherData
            );

            return;
        }

        // Проверяем, существует ли файл, и создаем его при необходимости
        if (!$this->filesystem->exists($this->logFile)) {
            $this->filesystem->touch($this->logFile);
        }

        $logEntry = sprintf(
            "%s - Погода в %s: %s°C, %s\n",
            date('Y-m-d H:i:s'),
            $weatherData['city'],
            $weatherData['temperature'],
            $weatherData['condition']
        );

        $this->filesystem->appendToFile($this->logFile, $logEntry);
        $this->logger->info('Лог погоды записан.', ['entry' => $logEntry]);
    }

}
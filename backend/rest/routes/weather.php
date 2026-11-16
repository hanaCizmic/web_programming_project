<?php
require_once __DIR__ . '/RouteResponder.php';
require_once __DIR__ . '/../services/WeatherService.php';

$weatherService = new WeatherService();

Flight::route('GET /weather', function () use ($weatherService) {
    RouteResponder::run(function () use ($weatherService) {
        $query = Flight::request()->query->getData();
        if (!array_key_exists('destination', $query)) {
            throw new InvalidArgumentException('destination query parameter is required.');
        }

        try {
            return RouteResponder::success(
                $weatherService->getCurrentWeather($query['destination'])
            );
        } catch (WeatherServiceException $exception) {
            return RouteResponder::error($exception->getMessage(), $exception->getHttpStatus());
        }
    });
});

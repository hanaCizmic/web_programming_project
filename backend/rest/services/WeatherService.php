<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/WeatherServiceException.php';

class WeatherService
{
    private $baseUrl = 'https://api.weatherapi.com/v1/current.json';

    public function getCurrentWeather($destination)
    {
        if (!is_string($destination)) {
            throw new InvalidArgumentException('destination must be a non-empty string.');
        }

        $destination = trim($destination);
        if ($destination === '' || strlen($destination) > 200) {
            throw new InvalidArgumentException('destination must contain between 1 and 200 characters.');
        }

        $apiKey = Config::WEATHER_API_KEY();
        if (!is_string($apiKey) || trim($apiKey) === '') {
            error_log('WeatherAPI is not configured: API key is missing or empty.');
            throw new WeatherServiceException('Weather service is not configured.', 503);
        }

        if (!function_exists('curl_init')) {
            error_log('WeatherAPI is unavailable: PHP cURL extension is not loaded.');
            throw new WeatherServiceException('Weather service is unavailable.', 503);
        }

        $url = $this->baseUrl . '?' . http_build_query([
            'key' => $apiKey,
            'q' => $destination,
            'aqi' => 'no'
        ]);

        $curl = curl_init($url);
        if ($curl === false) {
            error_log('WeatherAPI request failed: could not initialize cURL.');
            throw new WeatherServiceException('Weather service is unavailable.', 502);
        }

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
            CURLOPT_USERAGENT => 'TripMate/1.0'
        ]);

        $responseBody = curl_exec($curl);
        $curlErrorNumber = curl_errno($curl);
        $httpStatus = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($responseBody === false) {
            error_log('WeatherAPI request failed: cURL error ' . $curlErrorNumber . '.');
            if ($curlErrorNumber === CURLE_OPERATION_TIMEDOUT) {
                throw new WeatherServiceException('The weather provider timed out. Please try again later.', 504);
            }

            throw new WeatherServiceException('Unable to connect to the weather provider. Please try again later.', 502);
        }

        $response = json_decode($responseBody, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($response)) {
            error_log(
                'WeatherAPI returned invalid JSON (HTTP status ' . $httpStatus
                . ', JSON error ' . json_last_error() . ': ' . json_last_error_msg() . ').'
            );
            if ($httpStatus === 429 || $httpStatus >= 500) {
                throw new WeatherServiceException('The weather provider is temporarily unavailable.', 503);
            }

            throw new WeatherServiceException('The weather provider returned an invalid response.', 502);
        }

        if ($httpStatus < 200 || $httpStatus >= 300 || isset($response['error'])) {
            throw $this->providerException($httpStatus, $response);
        }

        if (!$this->hasValidWeatherData($response)) {
            error_log('WeatherAPI returned incomplete weather data (HTTP status ' . $httpStatus . ').');
            throw new WeatherServiceException('The weather provider returned incomplete weather data.', 502);
        }

        return [
            'location' => [
                'name' => $response['location']['name'],
                'region' => $response['location']['region'],
                'country' => $response['location']['country'],
                'local_time' => $response['location']['localtime']
            ],
            'current' => [
                'last_updated' => $response['current']['last_updated'],
                'temperature_c' => (float) $response['current']['temp_c'],
                'feels_like_c' => (float) $response['current']['feelslike_c'],
                'condition' => $response['current']['condition']['text'],
                'condition_icon' => $response['current']['condition']['icon'],
                'humidity_percent' => (int) $response['current']['humidity'],
                'wind_kph' => (float) $response['current']['wind_kph'],
                'wind_direction' => $response['current']['wind_dir'],
                'precipitation_mm' => (float) $response['current']['precip_mm'],
                'uv' => (float) $response['current']['uv']
            ]
        ];
    }

    private function hasValidWeatherData(array $response)
    {
        if (!isset($response['location'], $response['current'], $response['current']['condition'])) {
            return false;
        }

        $location = $response['location'];
        $current = $response['current'];
        $condition = $current['condition'];

        return isset(
            $location['name'],
            $location['region'],
            $location['country'],
            $location['localtime'],
            $current['last_updated'],
            $current['temp_c'],
            $current['feelslike_c'],
            $current['humidity'],
            $current['wind_kph'],
            $current['wind_dir'],
            $current['precip_mm'],
            $current['uv'],
            $condition['text'],
            $condition['icon']
        )
            && is_string($location['name'])
            && is_string($location['region'])
            && is_string($location['country'])
            && is_string($location['localtime'])
            && is_string($current['last_updated'])
            && is_numeric($current['temp_c'])
            && is_numeric($current['feelslike_c'])
            && is_numeric($current['humidity'])
            && is_numeric($current['wind_kph'])
            && is_string($current['wind_dir'])
            && is_numeric($current['precip_mm'])
            && is_numeric($current['uv'])
            && is_string($condition['text'])
            && is_string($condition['icon']);
    }

    private function providerException($httpStatus, array $response)
    {
        $providerCode = isset($response['error']['code']) ? (int) $response['error']['code'] : 0;
        error_log(
            'WeatherAPI request failed (HTTP status ' . $httpStatus
            . ', provider error code ' . $providerCode . ').'
        );

        if ($providerCode === 1006) {
            return new WeatherServiceException('No weather location matched the requested destination.', 404);
        }
        if (in_array($providerCode, [1002, 2006, 2008, 2009], true) || in_array($httpStatus, [401, 403], true)) {
            return new WeatherServiceException('The weather provider could not authorize the request.', 502);
        }
        if ($providerCode === 2007 || $httpStatus === 429) {
            return new WeatherServiceException('The weather API request limit has been reached. Please try again later.', 503);
        }
        if ($httpStatus >= 500) {
            return new WeatherServiceException('The weather provider is temporarily unavailable.', 503);
        }

        return new WeatherServiceException('The weather provider could not process the request.', 502);
    }
}

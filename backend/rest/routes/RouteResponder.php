<?php
require_once __DIR__ . '/../../vendor/autoload.php';

class RouteResponder
{
    public static function requestData()
    {
        $request = Flight::request();
        $data = $request->data->getData();
        $body = $request->getBody();

        if ($body !== '') {
            $decoded = json_decode($body, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }

            if (stripos($request->type, 'application/json') === 0) {
                throw new InvalidArgumentException('Request body must be a valid JSON object.');
            }
        }

        return $data;
    }

    public static function run(callable $action)
    {
        try {
            $result = $action();
            Flight::json($result['body'], $result['status']);
        } catch (InvalidArgumentException $exception) {
            Flight::json(['error' => $exception->getMessage()], 400);
        } catch (Throwable $exception) {
            error_log('TripMate REST API error: ' . $exception);
            Flight::json(['error' => 'Internal server error.'], 500);
        }
    }

    public static function success($data, $status = 200)
    {
        return ['status' => $status, 'body' => ['data' => $data]];
    }

    public static function error($message, $status)
    {
        return ['status' => $status, 'body' => ['error' => $message]];
    }

    public static function item($data)
    {
        return $data === null
            ? ['status' => 404, 'body' => ['error' => 'Resource not found.']]
            : self::success($data);
    }

    public static function deleted()
    {
        return ['status' => 200, 'body' => ['message' => 'Resource deleted successfully.']];
    }

    public static function notFound()
    {
        return ['status' => 404, 'body' => ['error' => 'Resource not found.']];
    }
}

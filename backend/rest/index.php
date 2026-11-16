<?php
if (PHP_SAPI === 'cli-server') {
    $requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    if (in_array($requestPath, ['/docs/swagger-ui.html', '/docs/openapi.yaml'], true)) {
        return false;
    }
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/routes/index.php';

Flight::start();

<?php
require_once __DIR__ . '/RouteResponder.php';
require_once __DIR__ . '/../services/DestinationService.php';

$destinationService = new DestinationService();

Flight::route('GET /destinations', function () use ($destinationService) {
    RouteResponder::run(function () use ($destinationService) {
        return RouteResponder::success($destinationService->getAll());
    });
});

Flight::route('GET /destinations/@id', function ($id) use ($destinationService) {
    RouteResponder::run(function () use ($destinationService, $id) {
        return RouteResponder::item($destinationService->getById($id));
    });
});

Flight::route('POST /destinations', function () use ($destinationService) {
    RouteResponder::run(function () use ($destinationService) {
        return RouteResponder::success(
            $destinationService->create(RouteResponder::requestData()),
            201
        );
    });
});

Flight::route('PATCH /destinations/@id', function ($id) use ($destinationService) {
    RouteResponder::run(function () use ($destinationService, $id) {
        return RouteResponder::item($destinationService->update($id, RouteResponder::requestData()));
    });
});

Flight::route('DELETE /destinations/@id', function ($id) use ($destinationService) {
    RouteResponder::run(function () use ($destinationService, $id) {
        return $destinationService->delete($id) ? RouteResponder::deleted() : RouteResponder::notFound();
    });
});

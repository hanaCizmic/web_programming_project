<?php
require_once __DIR__ . '/RouteResponder.php';
require_once __DIR__ . '/../services/TripService.php';

$tripService = new TripService();

Flight::route('GET /trips', function () use ($tripService) {
    RouteResponder::run(function () use ($tripService) {
        return RouteResponder::success($tripService->getAll());
    });
});

Flight::route('GET /trips/@id', function ($id) use ($tripService) {
    RouteResponder::run(function () use ($tripService, $id) {
        return RouteResponder::item($tripService->getById($id));
    });
});

Flight::route('POST /trips', function () use ($tripService) {
    RouteResponder::run(function () use ($tripService) {
        return RouteResponder::success(
            $tripService->create(RouteResponder::requestData()),
            201
        );
    });
});

Flight::route('PATCH /trips/@id', function ($id) use ($tripService) {
    RouteResponder::run(function () use ($tripService, $id) {
        return RouteResponder::item($tripService->update($id, RouteResponder::requestData()));
    });
});

Flight::route('DELETE /trips/@id', function ($id) use ($tripService) {
    RouteResponder::run(function () use ($tripService, $id) {
        return $tripService->delete($id) ? RouteResponder::deleted() : RouteResponder::notFound();
    });
});

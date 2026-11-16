<?php
require_once __DIR__ . '/RouteResponder.php';
require_once __DIR__ . '/../services/PackingItemService.php';

$packingItemService = new PackingItemService();

Flight::route('GET /packing-items', function () use ($packingItemService) {
    RouteResponder::run(function () use ($packingItemService) {
        return RouteResponder::success($packingItemService->getAll());
    });
});

Flight::route('GET /packing-items/@id', function ($id) use ($packingItemService) {
    RouteResponder::run(function () use ($packingItemService, $id) {
        return RouteResponder::item($packingItemService->getById($id));
    });
});

Flight::route('POST /packing-items', function () use ($packingItemService) {
    RouteResponder::run(function () use ($packingItemService) {
        return RouteResponder::success(
            $packingItemService->create(RouteResponder::requestData()),
            201
        );
    });
});

Flight::route('PATCH /packing-items/@id', function ($id) use ($packingItemService) {
    RouteResponder::run(function () use ($packingItemService, $id) {
        return RouteResponder::item($packingItemService->update($id, RouteResponder::requestData()));
    });
});

Flight::route('DELETE /packing-items/@id', function ($id) use ($packingItemService) {
    RouteResponder::run(function () use ($packingItemService, $id) {
        return $packingItemService->delete($id) ? RouteResponder::deleted() : RouteResponder::notFound();
    });
});

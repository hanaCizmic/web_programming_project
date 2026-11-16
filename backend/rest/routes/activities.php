<?php
require_once __DIR__ . '/RouteResponder.php';
require_once __DIR__ . '/../services/ActivityService.php';

$activityService = new ActivityService();

Flight::route('GET /activities', function () use ($activityService) {
    RouteResponder::run(function () use ($activityService) {
        return RouteResponder::success($activityService->getAll());
    });
});

Flight::route('GET /activities/@id', function ($id) use ($activityService) {
    RouteResponder::run(function () use ($activityService, $id) {
        return RouteResponder::item($activityService->getById($id));
    });
});

Flight::route('POST /activities', function () use ($activityService) {
    RouteResponder::run(function () use ($activityService) {
        return RouteResponder::success(
            $activityService->create(RouteResponder::requestData()),
            201
        );
    });
});

Flight::route('PATCH /activities/@id', function ($id) use ($activityService) {
    RouteResponder::run(function () use ($activityService, $id) {
        return RouteResponder::item($activityService->update($id, RouteResponder::requestData()));
    });
});

Flight::route('DELETE /activities/@id', function ($id) use ($activityService) {
    RouteResponder::run(function () use ($activityService, $id) {
        return $activityService->delete($id) ? RouteResponder::deleted() : RouteResponder::notFound();
    });
});

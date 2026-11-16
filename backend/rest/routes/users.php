<?php
require_once __DIR__ . '/RouteResponder.php';
require_once __DIR__ . '/../services/UserService.php';

$userService = new UserService();

Flight::route('GET /users', function () use ($userService) {
    RouteResponder::run(function () use ($userService) {
        return RouteResponder::success($userService->getAll());
    });
});

Flight::route('GET /users/@id', function ($id) use ($userService) {
    RouteResponder::run(function () use ($userService, $id) {
        return RouteResponder::item($userService->getById($id));
    });
});

Flight::route('POST /users', function () use ($userService) {
    RouteResponder::run(function () use ($userService) {
        return RouteResponder::success(
            $userService->create(RouteResponder::requestData()),
            201
        );
    });
});

Flight::route('PATCH /users/@id', function ($id) use ($userService) {
    RouteResponder::run(function () use ($userService, $id) {
        return RouteResponder::item($userService->update($id, RouteResponder::requestData()));
    });
});

Flight::route('DELETE /users/@id', function ($id) use ($userService) {
    RouteResponder::run(function () use ($userService, $id) {
        return $userService->delete($id) ? RouteResponder::deleted() : RouteResponder::notFound();
    });
});

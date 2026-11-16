<?php
require_once __DIR__ . '/RouteResponder.php';
require_once __DIR__ . '/../services/ExpenseService.php';

$expenseService = new ExpenseService();

Flight::route('GET /expenses', function () use ($expenseService) {
    RouteResponder::run(function () use ($expenseService) {
        return RouteResponder::success($expenseService->getAll());
    });
});

Flight::route('GET /expenses/@id', function ($id) use ($expenseService) {
    RouteResponder::run(function () use ($expenseService, $id) {
        return RouteResponder::item($expenseService->getById($id));
    });
});

Flight::route('POST /expenses', function () use ($expenseService) {
    RouteResponder::run(function () use ($expenseService) {
        return RouteResponder::success(
            $expenseService->create(RouteResponder::requestData()),
            201
        );
    });
});

Flight::route('PATCH /expenses/@id', function ($id) use ($expenseService) {
    RouteResponder::run(function () use ($expenseService, $id) {
        return RouteResponder::item($expenseService->update($id, RouteResponder::requestData()));
    });
});

Flight::route('DELETE /expenses/@id', function ($id) use ($expenseService) {
    RouteResponder::run(function () use ($expenseService, $id) {
        return $expenseService->delete($id) ? RouteResponder::deleted() : RouteResponder::notFound();
    });
});

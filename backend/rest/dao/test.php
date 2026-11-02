<?php

require_once __DIR__ . '/UserDao.php';
require_once __DIR__ . '/TripDao.php';
require_once __DIR__ . '/DestinationDao.php';
require_once __DIR__ . '/ActivityDao.php';
require_once __DIR__ . '/ExpenseDao.php';
require_once __DIR__ . '/PackingItemDao.php';


function print_result($title, $result)
{
    echo PHP_EOL . "--- " . $title . " ---" . PHP_EOL;

    // Do not display password hashes in test output.
    if (is_array($result)) {
        foreach ($result as $key => $value) {
            if ($key === 'password_hash') {
                $result[$key] = '[HIDDEN]';
            }
        }
    }

    print_r($result);
}


function check($condition, $message)
{
    if (!$condition) {
        throw new Exception("TEST FAILED: " . $message);
    }

    echo "[PASS] " . $message . PHP_EOL;
}


$userId = null;
$tripId = null;
$destinationId = null;
$activityId = null;
$expenseId = null;
$packingItemId = null;

try {
    // Create DAO objects.
    $userDao = new UserDao();
    $tripDao = new TripDao();
    $destinationDao = new DestinationDao();
    $activityDao = new ActivityDao();
    $expenseDao = new ExpenseDao();
    $packingItemDao = new PackingItemDao();

    echo "====================================" . PHP_EOL;
    echo "       TRIPMATE DAO TESTS" . PHP_EOL;
    echo "====================================" . PHP_EOL;


    // ====================================
    // 1. CREATE TEST RECORDS
    // ====================================

    echo PHP_EOL . "1. CREATING TEST RECORDS" . PHP_EOL;

    $uniqueEmail = 'dao_test_' . uniqid() . '@tripmate.com';

    $user = $userDao->add([
        'name' => 'DAO Test User',
        'email' => $uniqueEmail,
        'password_hash' => password_hash('TestPassword123!', PASSWORD_DEFAULT),
        'role' => 'user'
    ]);

    $userId = $user['id'];

    check($userId !== null, 'User created');


    $trip = $tripDao->add([
        'user_id' => $userId,
        'name' => 'DAO Test Trip',
        'destination' => 'Italy',
        'start_date' => '2030-07-10',
        'end_date' => '2030-07-17',
        'budget' => 1500.00,
        'currency' => 'EUR',
        'description' => 'Temporary trip for DAO testing'
    ]);

    $tripId = $trip['id'];

    check($tripId !== null, 'Trip created');


    $destination = $destinationDao->add([
        'trip_id' => $tripId,
        'name' => 'Rome',
        'country' => 'Italy'
    ]);

    $destinationId = $destination['id'];

    check($destinationId !== null, 'Destination created');


    $activity = $activityDao->add([
        'trip_id' => $tripId,
        'destination_id' => $destinationId,
        'title' => 'Visit the Colosseum',
        'description' => 'Visit the historic landmark',
        'activity_date' => '2030-07-11',
        'start_time' => '10:00:00'
    ]);

    $activityId = $activity['id'];

    check($activityId !== null, 'Activity created');


    $expense = $expenseDao->add([
        'trip_id' => $tripId,
        'category' => 'Food',
        'amount' => 45.00,
        'currency' => 'EUR',
        'expense_date' => '2030-07-11',
        'description' => 'Lunch'
    ]);

    $expenseId = $expense['id'];

    check($expenseId !== null, 'Expense created');


    $packingItem = $packingItemDao->add([
        'trip_id' => $tripId,
        'item_name' => 'Passport',
        'is_packed' => 0
    ]);

    $packingItemId = $packingItem['id'];

    check($packingItemId !== null, 'Packing item created');


    // ====================================
    // 2. TEST READ METHODS
    // ====================================

    echo PHP_EOL . "2. TESTING READ METHODS" . PHP_EOL;

    check(
        $userDao->get_by_id($userId) !== null,
        'User get_by_id()'
    );

    check(
        $tripDao->get_by_id($tripId) !== null,
        'Trip get_by_id()'
    );

    check(
        $destinationDao->get_by_id($destinationId) !== null,
        'Destination get_by_id()'
    );

    check(
        $activityDao->get_by_id($activityId) !== null,
        'Activity get_by_id()'
    );

    check(
        $expenseDao->get_by_id($expenseId) !== null,
        'Expense get_by_id()'
    );

    check(
        $packingItemDao->get_by_id($packingItemId) !== null,
        'Packing item get_by_id()'
    );


    // ====================================
    // 3. TEST UPDATE METHODS
    // ====================================

    echo PHP_EOL . "3. TESTING UPDATE METHODS" . PHP_EOL;

    $userDao->update([
        'name' => 'Updated DAO Test User'
    ], $userId);

    check(
        $userDao->get_by_id($userId)['name'] === 'Updated DAO Test User',
        'User update()'
    );


    $tripDao->update([
        'name' => 'Updated DAO Test Trip'
    ], $tripId);

    check(
        $tripDao->get_by_id($tripId)['name'] === 'Updated DAO Test Trip',
        'Trip update()'
    );


    $destinationDao->update([
        'name' => 'Updated Rome'
    ], $destinationId);

    check(
        $destinationDao->get_by_id($destinationId)['name'] === 'Updated Rome',
        'Destination update()'
    );


    $activityDao->update([
        'title' => 'Updated Colosseum Visit'
    ], $activityId);

    check(
        $activityDao->get_by_id($activityId)['title'] === 'Updated Colosseum Visit',
        'Activity update()'
    );


    $expenseDao->update([
        'amount' => 50.00
    ], $expenseId);

    check(
        (float) $expenseDao->get_by_id($expenseId)['amount'] === 50.00,
        'Expense update()'
    );


    $packingItemDao->update([
        'is_packed' => 1
    ], $packingItemId);

    check(
        (int) $packingItemDao->get_by_id($packingItemId)['is_packed'] === 1,
        'Packing item update()'
    );


    // ====================================
    // 4. TEST CUSTOM DAO METHODS
    // ====================================

    echo PHP_EOL . "4. TESTING CUSTOM DAO METHODS" . PHP_EOL;

    check(
        $userDao->get_by_email($uniqueEmail) !== null,
        'User get_by_email()'
    );

    check(
        count($tripDao->get_by_user_id($userId)) > 0,
        'Trip get_by_user_id()'
    );

    check(
        count($tripDao->get_upcoming_by_user_id($userId)) > 0,
        'Trip get_upcoming_by_user_id()'
    );

    check(
        count($destinationDao->get_by_trip_id($tripId)) > 0,
        'Destination get_by_trip_id()'
    );

    check(
        count($activityDao->get_by_trip_id($tripId)) > 0,
        'Activity get_by_trip_id()'
    );

    check(
        count($activityDao->get_by_destination_id($destinationId)) > 0,
        'Activity get_by_destination_id()'
    );

    check(
        count($activityDao->get_by_date($tripId, '2030-07-11')) > 0,
        'Activity get_by_date()'
    );

    check(
        count($expenseDao->get_by_trip_id($tripId)) > 0,
        'Expense get_by_trip_id()'
    );

    check(
        (float) $expenseDao->get_total_by_trip_id($tripId) === 50.00,
        'Expense get_total_by_trip_id()'
    );

    check(
        count($packingItemDao->get_by_trip_id($tripId)) > 0,
        'Packing item get_by_trip_id()'
    );

    check(
        count($packingItemDao->get_unpacked_by_trip_id($tripId)) === 0,
        'Packing item get_unpacked_by_trip_id()'
    );


    // ====================================
    // 5. TEST GET ALL AND COUNT METHODS
    // ====================================

    echo PHP_EOL . "5. TESTING GENERAL DAO METHODS" . PHP_EOL;

    check(count($userDao->get_all()) > 0, 'User get_all()');
    check(count($tripDao->get_all()) > 0, 'Trip get_all()');
    check(count($destinationDao->get_all()) > 0, 'Destination get_all()');
    check(count($activityDao->get_all()) > 0, 'Activity get_all()');
    check(count($expenseDao->get_all()) > 0, 'Expense get_all()');
    check(count($packingItemDao->get_all()) > 0, 'Packing item get_all()');

    check($userDao->count_all() > 0, 'User count_all()');
    check($tripDao->count_all() > 0, 'Trip count_all()');
    check($destinationDao->count_all() > 0, 'Destination count_all()');
    check($activityDao->count_all() > 0, 'Activity count_all()');
    check($expenseDao->count_all() > 0, 'Expense count_all()');
    check($packingItemDao->count_all() > 0, 'Packing item count_all()');


    // ====================================
    // 6. TEST DELETE METHODS
    // ====================================

    echo PHP_EOL . "6. TESTING DELETE METHODS" . PHP_EOL;

    // Delete child records before parent records.
    $packingItemDao->delete($packingItemId);
    check(
        $packingItemDao->get_by_id($packingItemId) === null,
        'Packing item delete()'
    );
    $packingItemId = null;


    $expenseDao->delete($expenseId);
    check(
        $expenseDao->get_by_id($expenseId) === null,
        'Expense delete()'
    );
    $expenseId = null;


    $activityDao->delete($activityId);
    check(
        $activityDao->get_by_id($activityId) === null,
        'Activity delete()'
    );
    $activityId = null;


    $destinationDao->delete($destinationId);
    check(
        $destinationDao->get_by_id($destinationId) === null,
        'Destination delete()'
    );
    $destinationId = null;


    $tripDao->delete($tripId);
    check(
        $tripDao->get_by_id($tripId) === null,
        'Trip delete()'
    );
    $tripId = null;


    $userDao->delete($userId);
    check(
        $userDao->get_by_id($userId) === null,
        'User delete()'
    );
    $userId = null;


    echo PHP_EOL . "====================================" . PHP_EOL;
    echo " ALL DAO TESTS COMPLETED SUCCESSFULLY" . PHP_EOL;
    echo "====================================" . PHP_EOL;

} catch (Throwable $e) {
    echo PHP_EOL . "[ERROR] " . $e->getMessage() . PHP_EOL;

} finally {

    // ====================================
    // CLEANUP
    // Remove any records left if a test fails.
    // ====================================

    echo PHP_EOL . "Cleaning up temporary test records..." . PHP_EOL;

    try {
        if ($packingItemId !== null) {
            $packingItemDao->delete($packingItemId);
        }

        if ($expenseId !== null) {
            $expenseDao->delete($expenseId);
        }

        if ($activityId !== null) {
            $activityDao->delete($activityId);
        }

        if ($destinationId !== null) {
            $destinationDao->delete($destinationId);
        }

        if ($tripId !== null) {
            $tripDao->delete($tripId);
        }

        if ($userId !== null) {
            $userDao->delete($userId);
        }

        echo "Cleanup completed." . PHP_EOL;

    } catch (Throwable $cleanupError) {
        echo "Cleanup error: " . $cleanupError->getMessage() . PHP_EOL;
    }
}
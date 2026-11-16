<?php
require_once __DIR__ . '/../dao/ActivityDao.php';
require_once __DIR__ . '/../dao/TripDao.php';
require_once __DIR__ . '/../dao/DestinationDao.php';
require_once __DIR__ . '/BaseService.php';

class ActivityService extends BaseService
{
    private $tripDao;
    private $destinationDao;

    public function __construct()
    {
        parent::__construct(new ActivityDao());
        $this->tripDao = new TripDao();
        $this->destinationDao = new DestinationDao();
    }

    protected function validatePayload($payload, $creating, $existing = null)
    {
        $payload = $this->assertFields(
            $payload,
            ['trip_id', 'destination_id', 'title', 'description', 'activity_date', 'start_time'],
            ['trip_id', 'title', 'activity_date'],
            $creating
        );

        if (array_key_exists('trip_id', $payload)) {
            $payload['trip_id'] = $this->positiveInteger($payload['trip_id'], 'trip_id');
        }
        if (array_key_exists('destination_id', $payload)) {
            $payload['destination_id'] = $payload['destination_id'] === null
                ? null
                : $this->positiveInteger($payload['destination_id'], 'destination_id');
        }
        if (array_key_exists('title', $payload)) {
            $payload['title'] = $this->requiredString($payload['title'], 'title', 150);
        }
        if (array_key_exists('description', $payload)) {
            $payload['description'] = $this->optionalText($payload['description'], 'description', 65535);
        }
        if (array_key_exists('activity_date', $payload)) {
            $payload['activity_date'] = $this->dateValue($payload['activity_date'], 'activity_date');
        }
        if (array_key_exists('start_time', $payload)) {
            $payload['start_time'] = $payload['start_time'] === null
                ? null
                : $this->timeValue($payload['start_time'], 'start_time');
        }

        $effective = array_merge($existing === null ? [] : $existing, $payload);
        if (isset($effective['trip_id']) && $this->tripDao->get_by_id($effective['trip_id']) === null) {
            throw new InvalidArgumentException('trip_id does not identify an existing trip.');
        }
        if (isset($effective['destination_id'])) {
            $destination = $this->destinationDao->get_by_id($effective['destination_id']);
            if ($destination === null) {
                throw new InvalidArgumentException('destination_id does not identify an existing destination.');
            }
            if ((int) $destination['trip_id'] !== (int) $effective['trip_id']) {
                throw new InvalidArgumentException('destination_id must belong to the activity trip.');
            }
        }

        return $payload;
    }
}

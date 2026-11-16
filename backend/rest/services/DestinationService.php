<?php
require_once __DIR__ . '/../dao/DestinationDao.php';
require_once __DIR__ . '/../dao/TripDao.php';
require_once __DIR__ . '/BaseService.php';

class DestinationService extends BaseService
{
    private $tripDao;

    public function __construct()
    {
        parent::__construct(new DestinationDao());
        $this->tripDao = new TripDao();
    }

    protected function validatePayload($payload, $creating, $existing = null)
    {
        $payload = $this->assertFields($payload, ['trip_id', 'name', 'country'], ['trip_id', 'name'], $creating);

        if (array_key_exists('trip_id', $payload)) {
            $payload['trip_id'] = $this->positiveInteger($payload['trip_id'], 'trip_id');
        }
        if (array_key_exists('name', $payload)) {
            $payload['name'] = $this->requiredString($payload['name'], 'name', 150);
        }
        if (array_key_exists('country', $payload)) {
            $payload['country'] = $this->optionalString($payload['country'], 'country', 100);
        }

        $effective = array_merge($existing === null ? [] : $existing, $payload);
        if (isset($effective['trip_id']) && $this->tripDao->get_by_id($effective['trip_id']) === null) {
            throw new InvalidArgumentException('trip_id does not identify an existing trip.');
        }

        return $payload;
    }
}

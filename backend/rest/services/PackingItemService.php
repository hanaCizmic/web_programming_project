<?php
require_once __DIR__ . '/../dao/PackingItemDao.php';
require_once __DIR__ . '/../dao/TripDao.php';
require_once __DIR__ . '/BaseService.php';

class PackingItemService extends BaseService
{
    private $tripDao;

    public function __construct()
    {
        parent::__construct(new PackingItemDao());
        $this->tripDao = new TripDao();
    }

    protected function validatePayload($payload, $creating, $existing = null)
    {
        $payload = $this->assertFields(
            $payload,
            ['trip_id', 'item_name', 'is_packed'],
            ['trip_id', 'item_name'],
            $creating
        );

        if (array_key_exists('trip_id', $payload)) {
            $payload['trip_id'] = $this->positiveInteger($payload['trip_id'], 'trip_id');
        }
        if (array_key_exists('item_name', $payload)) {
            $payload['item_name'] = $this->requiredString($payload['item_name'], 'item_name', 150);
        }
        if (array_key_exists('is_packed', $payload)) {
            $value = $payload['is_packed'];
            if (!in_array($value, [true, false, 0, 1, '0', '1'], true)) {
                throw new InvalidArgumentException('is_packed must be a boolean value.');
            }
            $payload['is_packed'] = (int) (bool) $value;
        }

        $effective = array_merge($existing === null ? [] : $existing, $payload);
        if (isset($effective['trip_id']) && $this->tripDao->get_by_id($effective['trip_id']) === null) {
            throw new InvalidArgumentException('trip_id does not identify an existing trip.');
        }

        return $payload;
    }
}

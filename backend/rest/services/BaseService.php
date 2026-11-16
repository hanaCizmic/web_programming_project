<?php

abstract class BaseService
{
    protected $dao;

    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    public function getAll()
    {
        return array_map([$this, 'formatRecord'], $this->dao->get_all());
    }

    public function getById($id)
    {
        $record = $this->dao->get_by_id($this->positiveInteger($id, 'id'));
        return $record === null ? null : $this->formatRecord($record);
    }

    public function create($payload)
    {
        $created = $this->dao->add($this->validatePayload($payload, true));
        $record = $this->dao->get_by_id($created['id']);
        return $this->formatRecord($record === null ? $created : $record);
    }

    public function update($id, $payload)
    {
        $id = $this->positiveInteger($id, 'id');
        $existing = $this->dao->get_by_id($id);
        if ($existing === null) {
            return null;
        }

        $values = $this->validatePayload($payload, false, $existing);
        $this->dao->update($values, $id);
        $record = $this->dao->get_by_id($id);
        return $record === null ? null : $this->formatRecord($record);
    }

    public function delete($id)
    {
        $id = $this->positiveInteger($id, 'id');
        return $this->dao->delete($id) > 0;
    }

    abstract protected function validatePayload($payload, $creating, $existing = null);

    protected function formatRecord(array $record)
    {
        return $record;
    }

    protected function assertFields($payload, array $allowed, array $required = [], $creating = false)
    {
        if (!is_array($payload)) {
            throw new InvalidArgumentException('Request body must be a JSON object.');
        }

        $unknown = array_diff(array_keys($payload), $allowed);
        if ($unknown) {
            throw new InvalidArgumentException('Unknown field: ' . reset($unknown));
        }

        if ($creating) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $payload) || $payload[$field] === null) {
                    throw new InvalidArgumentException($field . ' is required.');
                }
            }
        }

        if (!$creating && count($payload) === 0) {
            throw new InvalidArgumentException('At least one field is required.');
        }

        return $payload;
    }

    protected function requiredString($value, $field, $maxLength)
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException($field . ' must be a string.');
        }

        $value = trim($value);
        if ($value === '') {
            throw new InvalidArgumentException($field . ' cannot be empty.');
        }
        if ($this->stringLength($value, $field) > $maxLength) {
            throw new InvalidArgumentException($field . ' exceeds the maximum length.');
        }

        return $value;
    }

    protected function optionalString($value, $field, $maxLength)
    {
        if ($value === null) {
            return null;
        }

        if (!is_string($value)) {
            throw new InvalidArgumentException($field . ' must be a string or null.');
        }

        $value = trim($value);
        if ($this->stringLength($value, $field) > $maxLength) {
            throw new InvalidArgumentException($field . ' exceeds the maximum length.');
        }

        return $value;
    }

    protected function stringLength($value, $field)
    {
        if (preg_match('//u', $value) !== 1) {
            throw new InvalidArgumentException($field . ' must contain valid UTF-8 text.');
        }

        return preg_match_all('/./us', $value);
    }

    protected function positiveInteger($value, $field)
    {
        if (!is_int($value) && (!is_string($value) || !ctype_digit($value))) {
            throw new InvalidArgumentException($field . ' must be a positive integer.');
        }

        $integer = filter_var($value, FILTER_VALIDATE_INT);
        if ($integer === false || $integer < 1) {
            throw new InvalidArgumentException($field . ' must be a positive integer.');
        }

        return $integer;
    }

    protected function dateValue($value, $field)
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException($field . ' must use YYYY-MM-DD format.');
        }

        $date = DateTime::createFromFormat('!Y-m-d', $value);
        $errors = DateTime::getLastErrors();
        if ($date === false
            || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0))
            || $date->format('Y-m-d') !== $value
        ) {
            throw new InvalidArgumentException($field . ' must use a valid YYYY-MM-DD date.');
        }

        return $value;
    }

    protected function optionalText($value, $field, $maxBytes)
    {
        $value = $this->optionalString($value, $field, $maxBytes);
        if ($value !== null && strlen($value) > $maxBytes) {
            throw new InvalidArgumentException($field . ' exceeds the maximum byte length.');
        }

        return $value;
    }

    protected function timeValue($value, $field)
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException($field . ' must use HH:MM or HH:MM:SS format.');
        }

        $format = strlen($value) === 5 ? '!H:i' : '!H:i:s';
        $time = DateTime::createFromFormat($format, $value);
        $errors = DateTime::getLastErrors();
        if ($time === false
            || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0))
        ) {
            throw new InvalidArgumentException($field . ' must be a valid time.');
        }

        return $time->format('H:i:s');
    }

    protected function currencyValue($value)
    {
        if (!is_string($value) || !preg_match('/^[A-Za-z]{3}$/', $value)) {
            throw new InvalidArgumentException('currency must be a three-letter code.');
        }

        return strtoupper($value);
    }

    protected function moneyValue($value, $field)
    {
        if (!is_numeric($value) || !is_finite((float) $value)) {
            throw new InvalidArgumentException($field . ' must be a number.');
        }

        $amount = (float) $value;
        if ($amount < 0 || $amount > 99999999.99 || round($amount, 2) != $amount) {
            throw new InvalidArgumentException($field . ' must be between 0 and 99999999.99 with at most two decimals.');
        }

        return number_format($amount, 2, '.', '');
    }
}

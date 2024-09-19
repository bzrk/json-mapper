<?php
declare(strict_types=1);

namespace Bzrk\JsonMapper\PropertySerializer;

use DateTime;
use DateTimeZone;

class UTCSerializer extends PropertySerializer
{
    private const FORMAT = 'Y-m-d H:i:s e';
    private const TIME_ZONE = 'UTC';

    public function serialize(mixed $data): mixed
    {
        if($data instanceof DateTime) {
            $tmp = clone $data;
            $tmp->setTimezone(new DateTimeZone(self::TIME_ZONE));
            return $tmp->format(self::FORMAT);
        }
        return null;
    }

    public function deserialize(mixed $data): mixed
    {
        return $data;
    }
}
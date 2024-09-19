<?php
declare(strict_types=1);

namespace Bzrk\JsonMapper\PropertySerializer;

class StdSerializer extends PropertySerializer
{
    public function serialize(mixed $data): mixed
    {
        return $data;
    }

    public function deserialize(mixed $data): mixed
    {
        return $data;
    }
}
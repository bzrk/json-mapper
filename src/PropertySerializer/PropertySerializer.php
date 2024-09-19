<?php
declare(strict_types=1);

namespace Bzrk\JsonMapper\PropertySerializer;

abstract class PropertySerializer
{
    final public function __construct()
    {
    }

    abstract public function serialize(mixed $data) : mixed;

    abstract public function deserialize(mixed $data) : mixed;
}
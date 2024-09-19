<?php
declare(strict_types=1);

namespace Bzrk\JsonMapper\Internal\JsonDeSerializer;

interface CreateStrategy
{
    public function createObject(string $class, array $values) : object;
}
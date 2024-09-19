<?php
declare(strict_types=1);

namespace Bzrk\JsonMapper\Internal\JsonDeSerializer\CreateStrategy;

use Bzrk\JsonMapper\Internal\JsonDeSerializer\CreateStrategy;

class ConstructorStrategy implements CreateStrategy
{
    public function createObject(string $class, array $values) : object {
        return new $class(...$values);
    }
}
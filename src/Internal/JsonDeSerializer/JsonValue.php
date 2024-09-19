<?php
declare(strict_types=1);

namespace Bzrk\JsonMapper\Internal\JsonDeSerializer;

class JsonValue
{
    public function __construct(
        public readonly string $name,
        public readonly mixed $value
    )
    {
    }
}
<?php
declare(strict_types=1);

namespace Bzrk\JsonMapper\Attributes;

use Attribute;
use Bzrk\JsonMapper\PropertySerializer\PropertySerializer;
use Bzrk\JsonMapper\PropertySerializer\StdSerializer;

#[Attribute(Attribute::TARGET_PROPERTY)]
class JsonProperty
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly PropertySerializer $serializer = new StdSerializer()
    )
    {
    }

    public function name(string $name) : string
    {
        return $this->name ?? $name;
    }

    public function serialize(mixed $value) : mixed
    {
        return $this->serializer->serialize($value);
    }
}
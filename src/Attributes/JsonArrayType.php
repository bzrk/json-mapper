<?php
declare(strict_types=1);

namespace Bzrk\JsonMapper\Attributes;

use Attribute;
use Bzrk\JsonMapper\JsonMapperException;
use Bzrk\JsonMapper\JsonType as DataType;

#[Attribute(Attribute::TARGET_PROPERTY)]
class JsonArrayType
{
    public readonly DataType $type;

    /**
     * @throws JsonMapperException
     */
    public function __construct(
        string $class,
    )
    {
        $this->type = DataType::listOf($class);
    }
}
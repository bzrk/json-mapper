<?php
declare(strict_types=1);

namespace Bzrk\JsonMapper;

use Bzrk\JsonMapper\Internal\JsonDeSerializer;
use Bzrk\JsonMapper\Internal\JsonSerializer;

class JsonMapper
{
    /**
     * @throws JsonMapperException
     */
    public function toJson(object|array $classOrList): string {
        return (new JsonSerializer($classOrList))->serialize();
    }

    /**
     * @throws JsonMapperException
     */
    public function fromString(string $json, JsonType $type): object|array {
        return (new JsonDeSerializer($json, $type))->deserialize();
    }
}
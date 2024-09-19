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
     * @template T
     * @param class-string<T> $className
     * @return T[]
     */
    public function listFromString(string $json, string $className): array {
        return (new JsonDeSerializer($json, JsonType::listOf($className)))->deserialize();
    }

    /**
     * @throws JsonMapperException
     * @template T
     * @param class-string<T> $className
     * @return T
     */
    public function objectFromString(string $json, string $className): object {
        return (new JsonDeSerializer($json, JsonType::instanceOf($className)))->deserialize();
    }
}
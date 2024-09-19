<?php

declare(strict_types=1);

namespace Bzrk\JsonMapper\Internal;

use Bzrk\JsonMapper\Internal\JsonDeSerializer\ObjectArrayHydrator;
use Bzrk\JsonMapper\JsonMapperException;
use Bzrk\JsonMapper\JsonType;
use Throwable;

class JsonDeSerializer {
    public function __construct(
        private readonly string $data,
        private readonly JsonType $type
    )
    {}

    /**
     * @throws JsonMapperException
     */
    public function deserialize() : object|array {
        try {
            return (new ObjectArrayHydrator(json_decode(
                json: $this->data,
                associative: false,
                flags: JSON_THROW_ON_ERROR
            )))->hydrate($this->type);
        } catch (Throwable $throwable) {
            throw new JsonMapperException(
                message: 'Can\'t deserialize',
                previous: $throwable
            );
        }
    }
}
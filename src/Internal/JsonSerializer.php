<?php

declare(strict_types=1);

namespace Bzrk\JsonMapper\Internal;

use Bzrk\JsonMapper\Internal\JsonSerializer\ObjectArrayHydrator;
use Bzrk\JsonMapper\JsonMapperException;
use Throwable;

class JsonSerializer {
    public function __construct(private readonly object|array $data)
    {}

    /**
     * @throws JsonMapperException
     */
    public function serialize() : string {
        try {
            return json_encode(
                (new ObjectArrayHydrator($this->data))->hydrate(),
                JSON_THROW_ON_ERROR
            );
        } catch (Throwable $throwable) {
            throw new JsonMapperException(
                message: 'Can\'t serialize',
                previous: $throwable
            );
        }
    }
}
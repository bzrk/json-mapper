<?php
declare(strict_types=1);

namespace Bzrk\JsonMapper\Internal\JsonSerializer;

use Bzrk\JsonMapper\JsonMapperException;
use BZRK\PHPStream\StreamException;
use BZRK\PHPStream\Streams;
use ReflectionObject;
use ReflectionProperty;
use Throwable;

class ObjectArrayHydrator
{
    public function __construct(private readonly object|array $data)
    {}


    /**
     * @throws JsonMapperException
     */
    public function hydrate() : object|array {
        try {
            if (is_array($this->data)) {
                return Streams::of(array_values($this->data))
                    ->map(fn(object $data) => $this->hydrateObject($data))
                    ->toList();
            }

            return $this->hydrateObject($this->data);
        } catch (Throwable $throwable) {
            throw new JsonMapperException(
                message: 'Can\'t serialize',
                previous: $throwable
            );
        }
    }

    /**
     * @throws StreamException
     */
    private function hydrateObject(object $object) : object
    {
        $reflector = new ReflectionObject($object);
        $properties = $reflector->getProperties(ReflectionProperty::IS_PUBLIC);

        $hydrator = new PropertyHydrator();
        return (object) Streams::of($properties)
            ->map(fn(ReflectionProperty $property) => $hydrator->hydrate($property, $object))
            ->notNull()
            ->toMap(
                fn(JsonValue $it) => $it->name,
                fn(JsonValue $it) => $it->value
            );
    }
}
<?php
declare(strict_types=1);

namespace Bzrk\JsonMapper\Internal\JsonDeSerializer;

use Bzrk\JsonMapper\Internal\JsonDeSerializer\CreateStrategy\ConstructorStrategy;
use Bzrk\JsonMapper\JsonMapperException;
use Bzrk\JsonMapper\JsonType;
use BZRK\PHPStream\StreamException;
use BZRK\PHPStream\Streams;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Throwable;

class ObjectArrayHydrator
{
    public function __construct(private readonly object|array $data)
    {}

    /**
     * @throws JsonMapperException
     */
    public function hydrate(JsonType $type): object|array
    {
        $type->validate($this->data);

        try {
            if (is_array($this->data)) {
                return Streams::of(array_values($this->data))
                    ->map(fn(object $data) => $this->hydrateObject($type->class, $data))
                    ->toList();
            }

            return $this->hydrateObject($type->class, $this->data);
        } catch (Throwable $throwable) {
            throw new JsonMapperException(
                message: 'Can\'t deserialize',
                previous: $throwable
            );
        }
    }

    /**
     * @throws StreamException
     * @throws ReflectionException
     */
    private function hydrateObject(string $class, object $object) : object
    {
        $reflector = new ReflectionClass($class);
        $properties = $reflector->getProperties(ReflectionProperty::IS_PUBLIC);

        $hydrator = new PropertyHydrator();
        $values = Streams::of($properties)
            ->map(fn(ReflectionProperty $property) => $hydrator->hydrate($property, $object))
            ->notNull()
            ->toMap(
                fn(JsonValue $it) => $it->name,
                fn(JsonValue $it) => $it->value
            );
        return $this->createObject($reflector, $values);
    }

    private function createObject(ReflectionClass $reflectionClass, array $data) : object {
        $strategy = new ConstructorStrategy();
        return $strategy->createObject($reflectionClass->name, $data);
    }
}
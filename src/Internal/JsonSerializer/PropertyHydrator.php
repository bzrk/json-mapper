<?php
declare(strict_types=1);

namespace Bzrk\JsonMapper\Internal\JsonSerializer;

use Bzrk\JsonMapper\Attributes\JsonIgnore;
use Bzrk\JsonMapper\Attributes\JsonProperty;
use Bzrk\JsonMapper\JsonMapperException;
use ReflectionProperty;

class PropertyHydrator
{
    /**
     * @throws JsonMapperException
     */
    public function hydrate(ReflectionProperty $property, object $object) : ?JsonValue {
        $ignoreAttributes = $property->getAttributes(JsonIgnore::class);
        if(count($ignoreAttributes) <= 0) {
            $jsonProperty = new JsonProperty();
            $jsonPropertyAttributes = $property->getAttributes(JsonProperty::class);
            if(count($jsonPropertyAttributes) > 0) {
                $jsonProperty = $jsonPropertyAttributes[0]->newInstance();
            }

            return new JsonValue(
                $this->name($jsonProperty, $property),
                $this->value($jsonProperty, $property, $object)
            );
        }
        return null;
    }

    private function name(JsonProperty $jsonProperty, ReflectionProperty $property): string {
        return $jsonProperty->name($property->name);
    }

    /**
     * @throws JsonMapperException
     */
    private function value(JsonProperty $jsonProperty, ReflectionProperty $property, object $object): float|bool|int|string|object|array|null
    {
        if(!$property->isInitialized($object)) {
            return null;
        }

        $value = $jsonProperty->serialize($property->getValue($object));

        if(null === $value) {
            return null;
        }

        if(is_scalar($value)) {
            return $value;
        }

        return (new ObjectArrayHydrator($value))->hydrate();
    }
}
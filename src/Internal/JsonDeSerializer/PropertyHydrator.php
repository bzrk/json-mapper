<?php
declare(strict_types=1);

namespace Bzrk\JsonMapper\Internal\JsonDeSerializer;

use Bzrk\JsonMapper\Attributes\JsonArrayType;
use Bzrk\JsonMapper\Attributes\JsonProperty;
use Bzrk\JsonMapper\JsonMapperException;
use Bzrk\JsonMapper\JsonType;
use ReflectionProperty;

class PropertyHydrator
{
    /**
     * @throws JsonMapperException
     */
    public function hydrate(ReflectionProperty $property, object $object) : JsonValue {
        $jsonProperty = new JsonProperty();
        $jsonPropertyAttributes = $property->getAttributes(JsonProperty::class);
        if(count($jsonPropertyAttributes) > 0) {
            $jsonProperty = $jsonPropertyAttributes[0]->newInstance();
        }

        $name = $jsonProperty->name($property->name);
        $value = $this->value($jsonProperty, $property, $object->{$name} ?? null);

        return new JsonValue($name, $value);
    }

    /**
     * @throws JsonMapperException
     */
    private function value(
        JsonProperty $jsonProperty,
        ReflectionProperty $property,
        float|bool|int|string|object|array|null $value): mixed {

        if(!$property->hasType()) {
            throw new JsonMapperException("$property->name without type is not allowed");
        }

        $propertyType = $property->getType()->getName();
        $allowedTypes = ['float','bool','string','array'];

        if(!(in_array($propertyType, $allowedTypes) || $propertyType !== 'object')) {
            throw new JsonMapperException("type $propertyType of $property->name is not allowed");
        }

        if(null === $value) {
            if($property->getType()->allowsNull()) {
                return null;
            }
            throw new JsonMapperException("$property->name not allowed null values");
        }

        if(is_scalar($value)) {
            $valueType = gettype($value);
            if($valueType === $propertyType) {
                return $value;
            }
            throw new JsonMapperException("$value is from type $valueType expected $propertyType");
        }

        if(is_object($value)) {
            return (new ObjectArrayHydrator($value))->hydrate(JsonType::instanceOf($propertyType));
        }

        if(is_array($value)) {
            $arrayJsonType = $property->getAttributes(JsonArrayType::class);
            if(count($arrayJsonType) <= 0) {
                throw new JsonMapperException("$property->name form type array must have a JsonArrayType attributes");
            }
            /** @var JsonArrayType $arrayJsonTypeAttribute */
            $arrayJsonTypeAttribute = $arrayJsonType[0]->newInstance();
            return (new ObjectArrayHydrator($value))->hydrate($arrayJsonTypeAttribute->type);
        }

        return null;
    }
}
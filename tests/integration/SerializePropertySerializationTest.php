<?php
declare(strict_types=1);

namespace integration;

use Bzrk\JsonMapper\Attributes\JsonProperty;
use Bzrk\JsonMapper\JsonMapper;
use Bzrk\JsonMapper\JsonMapperException;
use Bzrk\JsonMapper\PropertySerializer\PropertySerializer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Constraint\JsonMatches;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertThat;

class SerializePropertySerializationTest extends TestCase
{
    /**
     * @throws JsonMapperException
     */
    #[Test]
    public function rename(): void {
        $data = new SerializePropertySerializationFixtureObject('name');
        $result = (new JsonMapper())->toJson($data);

        assertThat($result, new JsonMatches(json_encode([
            'type' => 'name###',
        ])));
    }
}

class SerializePropertySerializationPropertySerializer extends PropertySerializer {
    public function serialize(mixed $data): mixed
    {
        if(is_string($data)) {
            return $data . '###';
        }
        return $data;
    }

    public function deserialize(mixed $data): mixed
    {
        return $data;
    }
}

class SerializePropertySerializationFixtureObject {
    public function __construct(
        #[JsonProperty(name: 'type', serializer: new SerializePropertySerializationPropertySerializer())]
        public readonly string $name,
    )
    {}
}
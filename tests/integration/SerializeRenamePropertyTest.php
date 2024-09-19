<?php
declare(strict_types=1);

namespace integration;

use Bzrk\JsonMapper\Attributes\JsonProperty;
use Bzrk\JsonMapper\JsonMapper;
use Bzrk\JsonMapper\JsonMapperException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Constraint\JsonMatches;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertThat;

class SerializeRenamePropertyTest extends TestCase
{
    /**
     * @throws JsonMapperException
     */
    #[Test]
    public function rename(): void {
        $data = new SerializeRenamePropertyFixtureParent(
            'name',
            new SerializeRenamePropertyFixtureChild('data')
        );
        $result = (new JsonMapper())->toJson($data);

        assertThat($result, new JsonMatches(json_encode([
            'type' => 'name',
            'child' => [
                'childType' => 'data'
            ],
        ])));
    }
}

class SerializeRenamePropertyFixtureParent {
    public function __construct(
        #[JsonProperty(name: 'type')]
        public readonly string $name,
        public readonly SerializeRenamePropertyFixtureChild $child
    )
    {}
}

class SerializeRenamePropertyFixtureChild {
    public function __construct(
        #[JsonProperty(name: 'childType')]
        public readonly string $name
    )
    {}
}
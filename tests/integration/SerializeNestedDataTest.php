<?php
declare(strict_types=1);

namespace integration;

use Bzrk\JsonMapper\JsonMapper;
use Bzrk\JsonMapper\JsonMapperException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Constraint\JsonMatches;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertJsonStringEqualsJsonFile;
use function PHPUnit\Framework\assertThat;

class SerializeNestedDataTest extends TestCase
{
    /**
     * @throws JsonMapperException
     */
    #[Test]
    public function nested() : void
    {
        $child = new class("child") {
            public function __construct(
                public readonly string $type,
            ) {
            }
        };

        $parent = new class("parent", $child) {
            public function __construct(
                public readonly string $type,
                public readonly object $child,
            ) {
            }
        };

        $result = (new JsonMapper())->toJson($parent);

        assertThat($result, new JsonMatches(json_encode([
            'type' => 'parent',
            'child' => [
                'type' => 'child'
            ],
        ])));
    }
}
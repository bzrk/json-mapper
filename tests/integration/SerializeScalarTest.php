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

class SerializeScalarTest extends TestCase
{
    /**
     * @throws JsonMapperException
     */
    #[Test]
    public function scalar() : void
    {
        $data = new class(1.0, true, 12, "data", null) {
            public function __construct(
                public readonly float $float,
                public readonly bool $bool,
                public readonly int $int,
                public readonly string $string,
                public readonly ?string $null,
            ) {
            }
        };

        $result = (new JsonMapper())->toJson($data);

        assertThat($result, new JsonMatches(json_encode([
            'float' => 1.0,
            'bool' => true,
            'int' => 12,
            'string' => 'data',
            'null' => null,
        ])));
    }
}
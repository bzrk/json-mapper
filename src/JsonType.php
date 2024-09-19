<?php
declare(strict_types=1);

namespace Bzrk\JsonMapper;

class JsonType
{
    private const TYPE_LIST = 'list';
    private const TYPE_INSTANCE = 'instance';

    /**
     * @throws JsonMapperException
     */
    private function __construct(
        private readonly string $type,
        public readonly string $class,
    )
    {
        if(!class_exists($class)) {
            throw new JsonMapperException("Class $this->class not exists");
        }
    }

    /**
     * @throws JsonMapperException
     */
    public function validate(array|object $data): void {
        if (is_array($data) && self::TYPE_INSTANCE === $this->type) {
            throw new JsonMapperException('Can\'t handle a List as Object');
        }

        if (is_object($data) && self::TYPE_LIST == $this->type) {
            throw new JsonMapperException('Can\'t handle a Object as List');
        }
    }

    /**
     * @throws JsonMapperException
     */
    public static function listOf(string $className) : self {
        return new self(self::TYPE_LIST, $className);
    }

    /**
     * @throws JsonMapperException
     */
    public static function instanceOf(string $className) : self {
        return new self(self::TYPE_INSTANCE, $className);
    }
}
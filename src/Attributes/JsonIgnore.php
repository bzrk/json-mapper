<?php
declare(strict_types=1);

namespace Bzrk\JsonMapper\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class JsonIgnore
{
    public function __construct()
    {
    }
}
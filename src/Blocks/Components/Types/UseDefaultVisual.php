<?php

namespace Nexly\Blocks\Components\Types;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class UseDefaultVisual
{
    public function __construct(
        protected ?string $geometry = null
    ) {
    }

    /**
     * @return string|null
     */
    public function getGeometry(): ?string
    {
        return $this->geometry;
    }
}

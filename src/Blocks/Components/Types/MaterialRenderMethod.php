<?php

namespace Nexly\Blocks\Components\Types;

enum MaterialRenderMethod: string
{
    case ALPHA_TEST = "alpha_test";
    case BLEND = "blend";
    case OPAQUE = "opaque";

    /**
     * Returns the name of the material render method.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the string value of the material render method.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Creates a MaterialRenderMethod from a string value.
     *
     * @param string $value
     * @return MaterialTarget|null
     */
    public static function fromString(string $value): ?self
    {
        return match ($value) {
            self::RENDER_METHOD_ALPHA_TEST->value => self::RENDER_METHOD_ALPHA_TEST,
            self::RENDER_METHOD_BLEND->value => self::RENDER_METHOD_BLEND,
            self::RENDER_METHOD_OPAQUE->value => self::RENDER_METHOD_OPAQUE,
            default => null,
        };
    }
}

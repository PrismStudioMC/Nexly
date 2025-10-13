<?php

namespace Nexly\Blocks\Components\Types;

enum MaterialRenderMethod: string
{
    case ALPHA_TEST = "alpha_test";
    case ALPHA_TEST_SINGLE_SIDED = "alpha_test_single_sided";
    case BLEND = "blend";
    case DOUBLE_SIDED = "double_sided";
    case ALPHA_TEST_TO_OPAQUE = "alpha_test_to_opaque";
    case OPAQUE = "opaque";
    case ALPHA_TEST_SINGLE_SIDED_TO_OPAQUE = "alpha_test_single_sided_to_opaque";
    case BLEND_TO_OPAQUE = "blend_to_opaque";

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

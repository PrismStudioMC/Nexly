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
}

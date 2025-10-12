<?php

declare(strict_types=1);

namespace Nexly\Blocks\Components\Types;

use pocketmine\nbt\tag\CompoundTag;

final class Material
{
    public function __construct(
        private readonly string $texture,
        private readonly MaterialTarget $target = MaterialTarget::ALL,
        private readonly MaterialRenderMethod $renderMethod = MaterialRenderMethod::OPAQUE,
        private readonly bool   $faceDimming = true,
        private readonly bool   $ambientOcclusion = true
    ) {
    }

    /**
     * @return MaterialTarget
     */
    public function getTarget(): MaterialTarget
    {
        return $this->target;
    }

    /**
     * Returns the material in the correct NBT format supported by the client.
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setString("texture", $this->texture)
            ->setString("render_method", $this->renderMethod->getValue())
            ->setByte("face_dimming", $this->faceDimming ? 1 : 0)
            ->setByte("ambient_occlusion", $this->ambientOcclusion ? 1 : 0);
    }
}

<?php

declare(strict_types=1);

namespace Nexly\Blocks\Components\Types;

use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\StringTag;

final class Material
{
    public function __construct(
        private readonly string               $texture,
        private readonly MaterialTarget       $target = MaterialTarget::ALL,
        private readonly MaterialRenderMethod $renderMethod = MaterialRenderMethod::OPAQUE,
        private readonly string               $tintMethod = "none",
        private readonly float                $ambientOcclusion = 1.0,
        private readonly bool                 $faceDimming = true,
        private readonly bool                 $isotropic = false,
        private readonly bool                 $packedBools = false,
    ) {
        if ($ambientOcclusion < 0.0) {
            throw new \InvalidArgumentException("Ambient occlusion cannot be negative.");
        }
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
            ->setTag("ambient_occlusion", new FloatTag($this->ambientOcclusion))
            ->setTag("packed_bools", new ByteTag($this->packedBools ? 1 : 0))
            ->setTag("render_method", new StringTag($this->renderMethod->getValue()))
            ->setTag("texture", new StringTag($this->texture))
            ->setTag("face_dimming", new ByteTag($this->faceDimming ? 1 : 0))
            ->setTag("isotropic", new ByteTag($this->isotropic ? 1 : 0))
            ->setTag("tint_method", new StringTag($this->tintMethod));
    }
}

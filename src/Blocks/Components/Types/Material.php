<?php

namespace Nexly\Blocks\Components\Types;

use InvalidArgumentException;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\StringTag;

class Material
{
    public function __construct(
        protected string $texture,
        protected string $target = MaterialTarget::ALL,
        protected string $renderMethod = RenderMethod::OPAQUE,
        protected string $tintMethod = TintMethod::NONE,
        protected float  $ambientOcclusion = 1.0,
        protected bool   $faceDimming = true,
        protected bool   $isotropic = false,
        protected bool   $packedBools = false,
        protected bool   $alphaMaskedTint = false,
    )
    {
        if ($ambientOcclusion < 0.0 || $ambientOcclusion > 10.0) {
            throw new InvalidArgumentException("Ambient occlusion must be between 0.0 and 10.0.");
        }
        if ($alphaMaskedTint && ($this->tintMethod === TintMethod::NONE || $this->renderMethod !== RenderMethod::OPAQUE)) {
            throw new InvalidArgumentException("Alpha-masked tint requires a non-none tint method and opaque rendering.");
        }
    }

    /**
     * @return string
     */
    public function getTarget(): string
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
            ->setTag("packed_bools", new ByteTag($this->packedBools))
            ->setTag("render_method", new StringTag($this->renderMethod))
            ->setTag("texture", new StringTag($this->texture))
            ->setTag("alpha_masked_tint", new ByteTag($this->alphaMaskedTint))
            ->setTag("face_dimming", new ByteTag($this->faceDimming))
            ->setTag("isotropic", new ByteTag($this->isotropic))
            ->setTag("tint_method", new StringTag($this->tintMethod));
    }
}

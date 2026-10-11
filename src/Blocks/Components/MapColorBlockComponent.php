<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class MapColorBlockComponent extends BlockComponent
{
    protected string $hexColor;

    /**
     * @param string|array{int,int,int} $color Hex #RRGGBB or three RGB channels.
     * @param string|null $tintMethod
     */
    public function __construct(
        string|array $color,
        protected ?string $tintMethod = null
    ) {
        if (is_array($color)) {
            $this->hexColor = sprintf('#%02X%02X%02X', ...$color);
        } else {
            $color = strtoupper($color);
            $this->hexColor = str_starts_with($color, '#') ? $color : '#' . $color;
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::MAP_COLOR;
    }

    /**
     * @return StringTag|CompoundTag
     */
    public function toNBT(): StringTag|CompoundTag
    {
        if ($this->tintMethod === null) {
            return new StringTag($this->hexColor);
        }

        return CompoundTag::create()
            ->setString('color', $this->hexColor)
            ->setString('tint_method', $this->tintMethod);
    }
}

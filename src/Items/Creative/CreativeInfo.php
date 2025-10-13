<?php

namespace Nexly\Items\Creative;

use Attribute;
use BackedEnum;
use pocketmine\inventory\CreativeCategory;

#[Attribute(Attribute::TARGET_CLASS)]
class CreativeInfo
{
    public function __construct(
        private ?CreativeCategory $category,
        private CreativeGroup|BackedEnum|null $group = null,
    ) {
    }

    /**
     * @return CreativeCategory|null
     */
    public function getCategory(): ?CreativeCategory
    {
        return $this->category;
    }

    /**
     * @return BackedEnum|CreativeGroup|null
     */
    public function getGroup(): BackedEnum|CreativeGroup|null
    {
        return $this->group;
    }
}

<?php

namespace Nexly\Items\Creative;

use Attribute;
use pocketmine\inventory\CreativeCategory;

#[Attribute(Attribute::TARGET_CLASS)]
class CreativeInfo
{
    public function __construct(
        private ?CreativeCategory $category,
        private ?CreativeGroup $group = null,
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
     * @return CreativeGroup|null
     */
    public function getGroup(): ?CreativeGroup
    {
        return $this->group;
    }
}

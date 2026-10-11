<?php

namespace Nexly\Items\Creative;

use Attribute;
use BackedEnum;
use pocketmine\inventory\CreativeCategory;

#[Attribute(Attribute::TARGET_CLASS)]
class CreativeInfo
{
    public function __construct(
        protected ?CreativeCategory $category,
        protected CreativeGroup|BackedEnum|null $group = null,
        protected bool $isHidden = false,
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

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->isHidden;
    }
}

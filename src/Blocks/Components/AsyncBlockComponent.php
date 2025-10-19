<?php

namespace Nexly\Blocks\Components;

use pocketmine\nbt\tag\Tag;
use pocketmine\network\mcpe\protocol\serializer\NetworkNbtSerializer;

class AsyncBlockComponent extends BlockComponent
{
    private Tag $nbt;

    public function __construct(
        private string $name,
        string $nbt,
    )
    {
        $this->nbt = (new NetworkNbtSerializer())->read(base64_decode($nbt))->getTag();
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Tag
     */
    public function toNBT(): Tag
    {
        return $this->nbt;
    }
}
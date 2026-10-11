<?php

namespace Nexly\Blocks\Vanilla;

use pocketmine\block\Block;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockTypeInfo;
use pocketmine\block\Flowable;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

class HeadBlock extends Flowable
{
    public const MIN_ROTATION = 0;
    public const MAX_ROTATION = 15;

    protected int $facing = Facing::NORTH;
    protected int $rotation = self::MIN_ROTATION;

    public function __construct(
        BlockIdentifier $idInfo,
        string          $name,
        protected string  $texture,
        BlockTypeInfo   $typeInfo
    ) {
        parent::__construct($idInfo, $name, $typeInfo);
    }

    /**
     * @return string
     */
    public function getTexture(): string
    {
        return $this->texture;
    }

    /**
     * @return bool
     */
    public function isTransparent(): bool
    {
        return false;
    }

    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        $w->facingExcept($this->facing, Facing::DOWN);
        $w->boundedIntAuto(self::MIN_ROTATION, self::MAX_ROTATION, $this->rotation);
    }

    public function getFacing(): int
    {
        return $this->facing;
    }

    /** @return $this */
    public function setFacing(int $facing): self
    {
        if ($facing === Facing::DOWN) {
            throw new \InvalidArgumentException("Skull may not face DOWN");
        }
        $this->facing = $facing;
        return $this;
    }

    public function getRotation(): int
    {
        return $this->rotation;
    }

    /** @return $this */
    public function setRotation(int $rotation): self
    {
        if ($rotation < self::MIN_ROTATION || $rotation > self::MAX_ROTATION) {
            throw new \InvalidArgumentException("Rotation must be in range " . self::MIN_ROTATION . " ... " . self::MAX_ROTATION);
        }
        $this->rotation = $rotation;
        return $this;
    }

    protected function recalculateCollisionBoxes(): array
    {
        $collisionBox = AxisAlignedBB::one()
            ->contract(0.25, 0, 0.25)
            ->trim(Facing::UP, 0.5);
        if ($this->facing !== Facing::UP) {
            $collisionBox = $collisionBox
                ->offsetTowards(Facing::opposite($this->facing), 0.25)
                ->offsetTowards(Facing::UP, 0.25);
        }
        return [$collisionBox];
    }

    public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool
    {
        if ($face === Facing::DOWN) {
            return false;
        }

        $this->facing = $face;
        if ($player !== null && $face === Facing::UP) {
            $this->rotation = ((int) floor(($player->getLocation()->getYaw() * 16 / 360) + 0.5)) & 0xf;
        }
        return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
    }
}

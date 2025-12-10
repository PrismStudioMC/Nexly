<?php

namespace Nexly\Blocks\Components\Types;

use pocketmine\math\Vector3;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\utils\RegistryTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static BoxCollision SLAB()
 * @method static BoxCollision FENCE_GATE()
 * @method static BoxCollision MOBHEAD()
 * @method static BoxCollision LADDER()
 * @method static BoxCollision FARMLAND()
 * @method static BoxCollision FLOWER()
 */
class BoxCollision
{
    use RegistryTrait;

    protected static function setup(): void
    {
        self::_registryRegister("slab", new BoxCollision(new Vector3(-8, 4, -8), new Vector3(16, 8, 16)));
        self::_registryRegister("fence_gate", new BoxCollision(new Vector3(-8, 0, -2), new Vector3(16, 18, 4)));
        self::_registryRegister("mobhead", new BoxCollision(new Vector3(-4, 0, -4), new Vector3(8, 8, 8)));
        self::_registryRegister("ladder", new BoxCollision(new Vector3(-8, 0, 5), new Vector3(16, 16, 3)));
        self::_registryRegister("farmland", new BoxCollision(new Vector3(-8, 0, -8), new Vector3(16, 15, 16)));
        self::_registryRegister("flower", new BoxCollision(new Vector3(-2, 0, -5), new Vector3(8, 10, 8)));
    }

    public static function checkInit(): void
    {
        if (self::$members === null) {
            self::$members = [];
            self::setup();
        }
    }

    public function __construct(
        private Vector3 $origin,
        private Vector3 $size,
    ) {
    }

    /**
     * @return Vector3
     */
    public function getOrigin(): Vector3
    {
        return $this->origin;
    }

    /**
     * @param Vector3 $origin
     */
    public function setOrigin(Vector3 $origin): void
    {
        $this->origin = $origin;
    }

    /**
     * @return Vector3
     */
    public function getSize(): Vector3
    {
        return $this->size;
    }

    /**
     * @param Vector3 $size
     */
    public function setSize(Vector3 $size): void
    {
        $this->size = $size;
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @param bool $latest
     * @return CompoundTag
     */
    public function toNBT(bool $latest): CompoundTag
    {
        if($latest) {
            $minX = 8 + $this->origin->getX();
            $minY = $this->origin->getY();
            $minZ = 8 + $this->origin->getZ();

            return CompoundTag::create()
                ->setTag("minX", new FloatTag($minX))
                ->setTag("minY", new FloatTag($minY))
                ->setTag("minZ", new FloatTag($minZ))
                ->setTag("maxX", new FloatTag($minX + $this->size->getX()))
                ->setTag("maxY", new FloatTag($minY + $this->size->getY()))
                ->setTag("maxZ", new FloatTag($minZ + $this->size->getZ()));
        }

        return CompoundTag::create()
            ->setTag("origin", new ListTag([
                new FloatTag($this->origin->getX()),
                new FloatTag($this->origin->getY()),
                new FloatTag($this->origin->getZ())
            ], NBT::TAG_Float))
            ->setTag("size", new ListTag([
                new FloatTag($this->size->getX()),
                new FloatTag($this->size->getY()),
                new FloatTag($this->size->getZ())
            ], NBT::TAG_Float));
    }
}
BoxCollision::checkInit();

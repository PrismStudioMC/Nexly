<?php

/*
 *  _   _           _
 * | \ | | _____  _| |_   _
 * |  \| |/ _ \ \/ / | | | |
 * | |\  |  __/>  <| | |_| |
 * |_| \_|\___/_/\_\_|\__, |
 *                    |___/
 *
 * Nexly - A modern PocketMine-MP framework for custom blocks and items.
 *
 * This project is free software: you can redistribute it and/or modify
 * it under the terms of the Apache License, Version 2.0 as published by
 * the Apache Software Foundation.
 *
 * @author Zwuiix-cmd
 * @link   https://github.com/Zwuiix-cmd/Nexly
 *
 *
 */

namespace Nexly\Blocks\Vanilla;

use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockTypeInfo;
use pocketmine\block\Ladder;

class NexlyLadder extends Ladder
{
	protected float $climbSpeed = 0.15; // Default climb speed

	public function __construct(BlockIdentifier $idInfo, string $name, float $climbSpeed, BlockTypeInfo $typeInfo)
	{
		parent::__construct($idInfo, $name, $typeInfo);
		$this->climbSpeed = $climbSpeed;
	}

	/**
	 * Determines the speed at which a player can climb this ladder.
	 */
	public function getClimbSpeed() : float
	{
		return $this->climbSpeed;
	}
}

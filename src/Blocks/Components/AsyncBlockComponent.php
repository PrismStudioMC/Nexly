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

namespace Nexly\Blocks\Components;

use pocketmine\nbt\tag\Tag;
use pocketmine\network\mcpe\protocol\serializer\NetworkNbtSerializer;
use function base64_decode;

class AsyncBlockComponent extends BlockComponent
{
	protected Tag $nbt;

	public function __construct(
		protected string $name,
		string $nbt,
	) {
		$this->nbt = (new NetworkNbtSerializer())->read(base64_decode($nbt, true))->getTag();
	}

	public function getName() : string
	{
		return $this->name;
	}

	public function toNBT() : Tag
	{
		return $this->nbt;
	}
}

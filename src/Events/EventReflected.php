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

namespace Nexly\Events;

use ReflectionFunction;
use WeakReference;

class EventReflected
{
	public function __construct(
		protected \Closure           $closure,
		protected ReflectionFunction $ref,
		protected bool               $isStatic,
		protected ?WeakReference     $instanceRef,
		protected ?int               $instanceId,
		protected int                $priority,
		protected bool               $handleCancelled,
	) {
	}

	public function getClosure() : \Closure
	{
		return $this->closure;
	}

	public function getRef() : ReflectionFunction
	{
		return $this->ref;
	}

	public function isStatic() : bool
	{
		return $this->isStatic;
	}

	public function getInstanceRef() : ?WeakReference
	{
		return $this->instanceRef;
	}

	public function getInstanceId() : ?int
	{
		return $this->instanceId;
	}

	public function getPriority() : int
	{
		return $this->priority;
	}

	public function isHandleCancelled() : bool
	{
		return $this->handleCancelled;
	}
}

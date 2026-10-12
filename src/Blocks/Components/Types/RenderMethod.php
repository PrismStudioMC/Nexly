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

namespace Nexly\Blocks\Components\Types;

class RenderMethod
{
	public const ALPHA_TEST = "alpha_test";
	public const ALPHA_TEST_SINGLE_SIDED = "alpha_test_single_sided";
	public const BLEND = "blend";
	public const DOUBLE_SIDED = "double_sided";
	public const ALPHA_TEST_TO_OPAQUE = "alpha_test_to_opaque";
	public const OPAQUE = "opaque";
	public const ALPHA_TEST_SINGLE_SIDED_TO_OPAQUE = "alpha_test_single_sided_to_opaque";
	public const BLEND_TO_OPAQUE = "blend_to_opaque";
}

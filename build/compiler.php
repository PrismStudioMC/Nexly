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

enum Style : string
{
	case Reset = "\033[0m";
	case Bold = "\033[1m";
	case Dim = "\033[2m";
	case Red = "\033[31m";
	case Green = "\033[32m";
	case Yellow = "\033[33m";
	case Cyan = "\033[36m";
}

function verify_environment() : void
{
	if (!Phar::canWrite()) {
		echo format_text("\n[ERROR] Phar creation is disabled in your php.ini.\n", Style::Red, Style::Bold);
		echo format_text("        Please set 'phar.readonly = 0' in your CLI php.ini file.\n\n", Style::Dim);
		exit(1);
	}
}

function get_build_files(string $basePath) : array
{
	$files = [];
	$directories = ['src', 'resources'];

	foreach ($directories as $dir) {
		$path = $basePath . DIRECTORY_SEPARATOR . $dir;
		if (!is_dir($path)) {
			continue;
		}

		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)
		);

		/** @var SplFileInfo $file */
		foreach ($iterator as $file) {
			$realPath = $file->getRealPath();
			$localPath = str_replace($basePath . DIRECTORY_SEPARATOR, '', $realPath);
			$localPath = str_replace('\\', '/', $localPath);
			$files[$localPath] = $realPath;
		}
	}

	$pluginYml = $basePath . DIRECTORY_SEPARATOR . 'plugin.yml';
	if (file_exists($pluginYml)) {
		$files['plugin.yml'] = $pluginYml;
	}

	return $files;
}

function compile_phar(string $outputPath, array $files) : void
{
	if (file_exists($outputPath)) {
		@unlink($outputPath);
	}

	$outDir = dirname($outputPath);
	if (!is_dir($outDir)) {
		mkdir($outDir, 0777, true);
	}

	$phar = new Phar($outputPath, 0, basename($outputPath));
	$phar->setSignatureAlgorithm(Phar::SHA1);
	$phar->startBuffering();

	foreach ($files as $localPath => $realPath) {
		$phar->addFile($realPath, $localPath);
	}

	$phar->setStub("<?php __HALT_COMPILER();");
	$phar->stopBuffering();
}

function print_header() : void
{
	echo "\n";
	echo format_text(" Nexly COMPILER ", Style::Bold, Style::Cyan);
	echo sprintf(" Target: %s\n\n", format_text("Nexly.phar", Style::Bold));
}

function print_summary(string $outputPath, int $fileCount, float $executionTime) : void
{
	$totalTime = number_format($executionTime, 2);
	$size = file_exists($outputPath) ? round(filesize($outputPath) / 1024 / 1024, 2) : 0;

	echo format_text("  [SUMMARY]", Style::Bold) . "\n";
	echo format_text("    Packaged  : ", Style::Dim) . format_text((string) $fileCount, Style::Cyan) . " files\n";
	echo format_text("    Size      : ", Style::Dim) . format_text($size . " MB", Style::Cyan) . "\n";
	echo format_text("    Time      : ", Style::Dim) . format_text($totalTime . "s", Style::Cyan) . "\n\n";

	echo "  " . format_text("✓ Build completed successfully.", Style::Green) . "\n\n";
}

function format_text(string $message, Style ...$styles) : string
{
	$formatted = '';
	foreach ($styles as $style) {
		$formatted .= $style->value;
	}
	return $formatted . $message . Style::Reset->value;
}

verify_environment();
print_header();

$basePath = dirname(__DIR__);
var_dump($basePath);
$outputPath = $basePath . '/Nexly.phar';

echo format_text("  » Gathering files and compiling...", Style::Dim) . "\r";

$startTime = microtime(true);
$files = get_build_files($basePath);
compile_phar($outputPath, $files);
$endTime = microtime(true);

echo str_repeat(" ", 60) . "\r";

print_summary($outputPath, count($files), $endTime - $startTime);

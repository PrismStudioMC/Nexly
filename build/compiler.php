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
	if (!class_exists('ZipArchive')) {
		echo format_text("\n[ERROR] ZipArchive extension is missing.\n", Style::Red, Style::Bold);
		echo format_text("        Please enable the zip extension in your php.ini to download libraries.\n\n", Style::Dim);
		exit(1);
	}
}

function get_yaml_value(string $yaml, string $key) : ?string
{
	if (preg_match('/^' . preg_quote($key, '/') . '\s*:\s*(.+)$/m', $yaml, $matches)) {
		return trim($matches[1], "\"' \t\n\r\0\x0B");
	}
	return null;
}

function get_target_namespace(string $basePath) : string
{
	$pluginYmlPath = $basePath . DIRECTORY_SEPARATOR . 'plugin.yml';
	if (!file_exists($pluginYmlPath)) {
		return "App";
	}

	$yaml = file_get_contents($pluginYmlPath);
	$prefix = get_yaml_value($yaml, 'src-namespace-prefix');
	if ($prefix) {
		return trim($prefix, '\\');
	}

	$main = get_yaml_value($yaml, 'main');
	if ($main) {
		$parts = explode('\\', $main);
		array_pop($parts);
		return implode('\\', $parts);
	}

	return "App";
}

function apply_global_shading(string $content, array $antigenMap) : string
{
	foreach ($antigenMap as $original => $new) {
		$content = preg_replace('/namespace\s+' . preg_quote($original, '/') . '(\s*[;\\\\])/m', 'namespace ' . $new . '$1', $content);

		$content = preg_replace('/use\s+(function\s+|const\s+)?' . preg_quote($original, '/') . '(\s*[;\\\\])/m', 'use $1' . $new . '$2', $content);

		$content = preg_replace('/(?<![\w\\\\])\\\\' . preg_quote($original, '/') . '(\s*[;\\\\])/m', '\\' . $new . '$1', $content);

		$originalStr = str_replace('\\', '\\\\', $original);
		$newStr = str_replace('\\', '\\\\', $new);
		$content = str_replace($originalStr, $newStr, $content);
	}

	return $content;
}

function process_libraries(string $basePath, string $targetNamespace) : array
{
	$libFiles = [];
	$antigenMap = [];
	$jsonPath = $basePath . DIRECTORY_SEPARATOR . 'libraries.json';

	if (!file_exists($jsonPath)) {
		return ['files' => $libFiles, 'map' => $antigenMap];
	}

	$libraries = json_decode(file_get_contents($jsonPath), true);
	if (!is_array($libraries)) {
		echo format_text("    [!] Invalid libraries.json format.\n", Style::Yellow);
		return ['files' => $libFiles, 'map' => $antigenMap];
	}

	$context = stream_context_create([
		'http' => [
			'header' => "User-Agent: Nexly-Compiler/1.0\r\n"
		]
	]);

	foreach ($libraries as $repo => $version) {
		if (str_starts_with($version, 'http')) {
			$url = $version;
			$displayName = basename($url);
		} else {
			$url = "https://github.com/{$repo}/archive/refs/tags/{$version}.zip";
			if ($version === 'main' || $version === 'master') {
				$url = "https://github.com/{$repo}/archive/refs/heads/{$version}.zip";
			}
			$displayName = "$repo ($version)";
		}

		echo format_text("    -> Downloading library: ", Style::Dim) . format_text($displayName, Style::Cyan) . "\n";

		$tmpZip = sys_get_temp_dir() . DIRECTORY_SEPARATOR . md5($url) . '.zip';
		$zipData = @file_get_contents($url, false, $context);

		if ($zipData === false) {
			echo format_text("       [!] Failed to download from $url\n", Style::Red);
			continue;
		}

		file_put_contents($tmpZip, $zipData);
		$zip = new ZipArchive();

		if ($zip->open($tmpZip) === true) {
			$rootDir = '';
			for ($i = 0; $i < $zip->numFiles; $i++) {
				$stat = $zip->statIndex($i);
				if (str_ends_with($stat['name'], 'virion.yml')) {
					$rootDir = dirname($stat['name']);
					$rootDir = $rootDir === '.' ? '' : $rootDir . '/';
					break;
				}
			}

			$virionYml = $zip->getFromName($rootDir . 'virion.yml');
			if ($virionYml) {
				$antigen = get_yaml_value($virionYml, 'antigen');
				if ($antigen) {
					$originalAntigen = trim($antigen, '\\');

					$antigenClean = str_replace('-', '', $originalAntigen);

					$newAntigen = trim($targetNamespace, '\\') . '\\Libs\\' . $antigenClean;

					$antigenMap[$originalAntigen] = $newAntigen;

					$antigenPath = str_replace('\\', '/', $antigenClean);

					for ($i = 0; $i < $zip->numFiles; $i++) {
						$stat = $zip->statIndex($i);
						$fileName = $stat['name'];

						if (str_starts_with($fileName, $rootDir . 'src/') && str_ends_with($fileName, '.php')) {
							$content = $zip->getFromIndex($i);

							$relativePath = substr($fileName, strlen($rootDir . 'src/'));
							$cleanRelativePath = ltrim(str_replace('-', '', $relativePath), '/');

							if (!str_starts_with($cleanRelativePath, $antigenPath . '/')) {
								$newLocalPath = 'src/Libs/' . $antigenPath . '/' . $cleanRelativePath;
							} else {
								$newLocalPath = 'src/Libs/' . $cleanRelativePath;
							}

							$libFiles[$newLocalPath] = $content;
						}
					}
				}
			} else {
				echo format_text("       [!] virion.yml not found in $displayName.\n", Style::Yellow);
			}
			$zip->close();
		} else {
			echo format_text("       [!] Failed to read downloaded ZIP for $displayName.\n", Style::Red);
		}

		@unlink($tmpZip);
	}

	uksort($antigenMap, fn($a, $b) => strlen($b) <=> strlen($a));

	return ['files' => $libFiles, 'map' => $antigenMap];
}

function get_build_files(string $basePath) : array
{
	$phpFiles = [];
	$otherFiles = [];
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

			if (pathinfo($realPath, PATHINFO_EXTENSION) === 'php' && str_starts_with($localPath, 'src/')) {
				$phpFiles[$localPath] = file_get_contents($realPath);
			} else {
				$otherFiles[$localPath] = $realPath;
			}
		}
	}

	$pluginYml = $basePath . DIRECTORY_SEPARATOR . 'plugin.yml';
	if (file_exists($pluginYml)) {
		$otherFiles['plugin.yml'] = $pluginYml;
	}

	return ['php' => $phpFiles, 'other' => $otherFiles];
}

function compile_phar(string $outputPath, array $physicalOtherFiles, array $virtualShadedFiles) : void
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

	foreach ($physicalOtherFiles as $localPath => $realPath) {
		$phar->addFile($realPath, $localPath);
	}

	foreach ($virtualShadedFiles as $localPath => $content) {
		$phar->addFromString($localPath, $content);
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
$outputPath = $basePath . '/Nexly.phar';
$startTime = microtime(true);

$targetNamespace = get_target_namespace($basePath);
echo format_text("  » Project Namespace : ", Style::Dim) . format_text($targetNamespace, Style::Cyan) . "\n";
echo format_text("  » Gathering files and injecting...\n", Style::Dim);

$pluginData = get_build_files($basePath);
$librariesData = process_libraries($basePath, $targetNamespace);

$pluginPhpFiles = $pluginData['php'];
$otherFiles = $pluginData['other'];

$rawLibFiles = $librariesData['files'];
$antigenMap = $librariesData['map'];

echo format_text("  » Remapping namespaces globally...\n", Style::Dim);
$finalVirtualFiles = [];

$allPhpFilesToShade = array_merge($pluginPhpFiles, $rawLibFiles);

foreach ($allPhpFilesToShade as $localPath => $rawContent) {
	if (!empty($antigenMap)) {
		$finalVirtualFiles[$localPath] = apply_global_shading($rawContent, $antigenMap);
	} else {
		$finalVirtualFiles[$localPath] = $rawContent;
	}
}

echo format_text("  » Compiling Phar...", Style::Dim) . "\r";
compile_phar($outputPath, $otherFiles, $finalVirtualFiles);

$endTime = microtime(true);
$totalFiles = count($otherFiles) + count($finalVirtualFiles);

echo str_repeat(" ", 60) . "\r";
print_summary($outputPath, $totalFiles, $endTime - $startTime);

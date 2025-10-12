<?php

require_once __DIR__ . '/vendor/autoload.php';

$paths = [];
$dry = false;
foreach ($argv as $a) {
    if (str_starts_with($a, '--path=')) $paths = array_filter(array_map('trim', explode(',', substr($a, 7))));
    if ($a === '--dry-run') $dry = true;
}
$paths = $paths ?: ['src'];

function iterPhp(array $roots): Generator {
    foreach ($roots as $r) if (is_dir($r)) {
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($r, FilesystemIterator::SKIP_DOTS));
        foreach ($it as $f) if (strtolower($f->getExtension()) === 'php') yield $f->getPathname();
    }
}

$rx = '/[\x{00A0}\x{1680}\x{2000}-\x{200B}\x{202F}\x{205F}\x{3000}\x{FEFF}]/u';
$files = $repl = 0;
foreach (iterPhp($paths) as $p) {
    $files++;
    $s = file_get_contents($p);
    $n = preg_replace($rx, ' ', $s, -1, $c);
    if ($c > 0) { $repl += $c; if (!$dry) file_put_contents($p, $n); echo "[normalize] $p — $c\n"; }
}
echo "Standardization summary: $files file(s) scanned, $repl space replacement(s).\n";

$config = getcwd() . '/.php-cs-fixer.generated.php';
$rules = [
    '@PSR12' => true,
];
file_put_contents($config, "<?php\nreturn (new PhpCsFixer\\Config())->setRiskyAllowed(true)->setRules("
    . var_export($rules, true)
    . ")->setFinder(PhpCsFixer\\Finder::create()->in(" . var_export($paths, true) . "));\n");

$isWin = DIRECTORY_SEPARATOR === '\\';
$bat = getcwd() . '\\vendor\\bin\\php-cs-fixer.bat';
$bin = getcwd() . '/vendor/bin/php-cs-fixer';
$exec = $isWin ? $bat : $bin;

if (!file_exists($exec)) {
    fwrite(STDERR, "Error: PHP-CS-Fixer not found. Run: composer require --dev friendsofphp/php-cs-fixer\n");
    exit(1);
}

$args = ' fix --config=' . escapeshellarg($config) . ' --allow-risky=yes' . ($dry ? ' --dry-run --diff --verbose' : '');
$cmd = $isWin ? escapeshellarg($exec) . $args : escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg($exec) . $args;

echo "\n[php-cs-fixer] $cmd\n\n";
passthru($cmd, $code);
if (!$dry && file_exists($config)) @unlink($config);
exit($code);
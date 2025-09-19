<?php
$path = __DIR__ . '/routes/api.php';
if (!file_exists($path)) {
    echo "MISSING\n";
    exit(1);
}
$s = file_get_contents($path);
echo "LEN:" . strlen($s) . "\n";
echo chunk_split(bin2hex($s), 2, ' ') . "\n";
echo "\n---TEXT---\n";
echo $s;

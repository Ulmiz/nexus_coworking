<?php
$brainDir = 'C:\Users\princ\.gemini\antigravity-ide\brain\fa312482-850c-4f6f-922f-174daab68079';
$targetDir = __DIR__ . '/assets/images';

if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$files = [
    'dashboard_hero_1779310482086.png' => 'dashboard_hero.png',
];

foreach ($files as $source => $dest) {
    if (file_exists("$brainDir\\$source")) {
        copy("$brainDir\\$source", "$targetDir/$dest");
        echo "Copied $dest <br>";
    } else {
        echo "Missing $source <br>";
    }
}
echo "Done!";

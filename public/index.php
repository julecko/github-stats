<?php

require __DIR__ . '/../src/config.php';

$config = load_env(__DIR__ . '/../.env');

if (!$config['github_token']) {
    http_response_code(500);
    exit('Missing GitHub token');
}

$user = $_GET['user'] ?? null;
if (!$user) {
    http_response_code(400);
    exit('Missing ?user=');
}

require __DIR__ . '/../src/github.php';
require __DIR__ . '/../src/stats.php';
require __DIR__ . '/../src/render.php';

try {
    $raw   = fetch_languages($user, $config['github_token']);
    $langs = aggregate_languages($raw);
    $svg   = render_svg($langs);
} catch (Throwable $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
    exit('Error generating stats');
}

header('Content-Type: image/svg+xml');
header('Cache-Control: public, max-age=3600');

echo $svg;

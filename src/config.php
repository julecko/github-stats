<?php

function load_env(string $path)
{
    if (!file_exists($path)) return;

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;

        [$key, $value] = explode('=', $line, 2);
        $value = trim($value);

        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
        putenv("$key=$value");
    }

    return [
        'github_token' => getenv('GITHUB_TOKEN'),
        'cache_ttl'    => (int)(getenv('CACHE_TTL') ?: 3600),
    ];
}

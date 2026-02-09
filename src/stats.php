<?php

function aggregate_languages(array $data): array
{
    $langs = [];

    $repos = $data['data']['user']['repositories']['nodes'] ?? [];

    foreach ($repos as $repo) {
        foreach ($repo['languages']['edges'] as $lang) {
            $name = $lang['node']['name'];
            $size = $lang['size'];

            $langs[$name] = ($langs[$name] ?? 0) + $size;
        }
    }

    arsort($langs);
    return $langs;
}

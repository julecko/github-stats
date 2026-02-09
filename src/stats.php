<?php

function aggregate_languages(array $data): array
{
    // $langs will hold: ['PHP' => ['size' => 12345, 'count' => 3], ...]
    $langs = [];

    $repos = $data['data']['user']['repositories']['nodes'] ?? [];

    foreach ($repos as $repo) {
        $repoLangs = $repo['languages']['edges'] ?? [];
        foreach ($repoLangs as $lang) {
            $name = $lang['node']['name'];
            $size = $lang['size'];

            if (!isset($langs[$name])) {
                $langs[$name] = ['size' => 0, 'count' => 0];
            }

            $langs[$name]['size'] += $size;
            $langs[$name]['count'] += 1;
        }
    }

    uasort($langs, fn($a, $b) => $b['size'] <=> $a['size']);

    return $langs;
}

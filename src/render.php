<?php

function render_svg(array $langs): string
{
    if (!$langs) {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="40">
            <text x="10" y="25">No data</text>
        </svg>';
    }

    $total = array_sum($langs);
    $maxWidth = 260;
    $y = 20;
    $barHeight = 16;
    $gap = 10;

    $svg = [];

    foreach ($langs as $lang => $size) {
        $pct = ($size / $total);
        $width = (int)($pct * $maxWidth);

        $svg[] = sprintf(
            '<text x="10" y="%d" font-size="12">%s</text>
             <rect x="120" y="%d" width="%d" height="%d" fill="#58a6ff"/>
             <text x="%d" y="%d" font-size="11">%.1f%%</text>',
            $y + 12,
            htmlspecialchars($lang),
            $y,
            $width,
            $barHeight,
            130 + $width,
            $y + 12,
            $pct * 100
        );

        $y += $barHeight + $gap;
    }

    $height = $y + 10;

    return sprintf(
        '<svg xmlns="http://www.w3.org/2000/svg" width="450" height="%d">%s</svg>',
        $height,
        implode('', $svg)
    );
}

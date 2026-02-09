<?php
function render_svg(array $langs, int $langs_count): string
{
    if (!$langs) {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="60">
            <text x="10" y="35" font-size="14" fill="#e6edf3">No data</text>
        </svg>';
    }

    $langs = array_slice($langs, 0, $langs_count, true);
    $totalSize = array_sum(array_column($langs, 'size'));

    $titleHeight   = 28;
    $barHeight     = 10;
    $gap           = 18;
    $xOffset       = 110;
    $barMaxWidth   = 260;
    $rightTextX    = $xOffset + $barMaxWidth + 10;

    $bgColor       = '#21262d';
    $textColor     = '#c9d1d9';
    $percColor     = '#8b949e';
    $titleColor    = '#c9d1d9';

    // GitHub's official language colors
    $colors = [
        'JavaScript'    => '#f1e05a',
        'TypeScript'    => '#3178c6',
        'HTML'          => '#e34c26',
        'CSS'           => '#563d7c',
        'Python'        => '#3572A5',
        'Java'          => '#b07219',
        'PHP'           => '#4F5D95',
        'C'             => '#555555',
        'C++'           => '#f34b7d',
        'C#'            => '#178600',
        'Go'            => '#00add8',
        'Rust'          => '#dea584',
        'Ruby'          => '#701516',
        'Shell'         => '#89e051',
        'Kotlin'        => '#a97bff',
        'Dart'          => '#00b4ab',
    ];

    $fontStack = "-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif";
    $fontSizeTitle = 22;
    $fontSizeText = 14;

    $elements = [];
    $y = $titleHeight + 12;

    // Title
    $elements[] = sprintf(
        '<text x="10" y="%d" font-size="%d" font-family="%s" fill="%s">Most Used Languages</text>',
        $titleHeight - 6, $fontSizeTitle, $fontStack, $titleColor
    );

    $i = 0;
    foreach ($langs as $lang => $info) {
        $size = $info['size'];
        $count = $info['count'] ?? 0;
        $pct = $totalSize ? ($size / $totalSize) : 0;
        $barWidth = (int) round($pct * $barMaxWidth);

        $barColor = $colors[$lang] ?? '#8b949e';

        // Background bar
        $elements[] = sprintf(
            '<rect x="%d" y="%d" width="%d" height="%d" rx="4" ry="4" fill="%s"/>',
            $xOffset, $y, $barMaxWidth, $barHeight, $bgColor
        );

        // Colored progress bar
        $elements[] = sprintf(
            '<rect x="%d" y="%d" width="%d" height="%d" rx="4" ry="4" fill="%s"/>',
            $xOffset, $y, $barWidth, $barHeight, $barColor
        );

        // Language name
        $elements[] = sprintf(
            '<text x="10" y="%d" font-size="%d" font-family="%s" fill="%s" dominant-baseline="middle">%s</text>',
            $y + $barHeight / 2, $fontSizeText, $fontStack, $textColor, htmlspecialchars($lang)
        );

        // Right side: percentage + project count
        $rightText = sprintf("%.1f%% (%d)", $pct * 100, $count);
        if ($count === 1) {
            $rightText = sprintf("%.1f%% (1 repo)", $pct * 100);
        }

        $elements[] = sprintf(
            '<text x="%d" y="%d" font-size="%d" font-family="%s" fill="%s" text-anchor="start" dominant-baseline="middle">%s</text>',
            $rightTextX, $y + $barHeight / 2, $fontSizeText - 1, $fontStack, $percColor, $rightText
        );

        $y += $barHeight + $gap;
        $i++;
    }

    $svgHeight = $y + 10;
    $svgWidth  = $xOffset + $barMaxWidth + 90; // more space for "xx.x% (n repos)"

    $borderWidth  = $svgWidth;
    $borderHeight = $svgHeight;

    $elements[] = sprintf(
        '<rect x="0" y="0" width="%d" height="%d" rx="10" ry="10" fill="none" stroke="#ffffff" stroke-width="2"/>',
        $borderWidth, $borderHeight
    );

    return sprintf(
        '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" style="background:#0d1117">%s</svg>',
        $svgWidth, $svgHeight, implode("\n", $elements)
    );
}

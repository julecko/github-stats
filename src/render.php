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

    $padding = [
        'top'    => 16,
        'right'  => 16,
        'bottom' => 16,
        'left'   => 16,
    ];

    $barHeight   = 10;
    $gap         = 18;
    $barMaxWidth = 260;

    $fontSizeTitle = 22;
    $fontSizeText  = 14;

    $titleSpacing = 25;

    $bgColor    = '#21262d';
    $textColor  = '#c9d1d9';
    $percColor  = '#8b949e';
    $titleColor = '#c9d1d9';

    $colors = [
        'JavaScript' => '#f1e05a',
        'TypeScript' => '#3178c6',
        'HTML'       => '#e34c26',
        'CSS'        => '#563d7c',
        'Python'     => '#3572A5',
        'Java'       => '#b07219',
        'PHP'        => '#4F5D95',
        'C'          => '#555555',
        'C++'        => '#f34b7d',
        'C#'         => '#178600',
        'Go'         => '#00add8',
        'Rust'       => '#dea584',
        'Ruby'       => '#701516',
        'Shell'      => '#89e051',
        'Kotlin'     => '#a97bff',
        'Dart'       => '#00b4ab',
        'Svelte'     => '#ff3e00',
    ];

    $fontStack = "-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif";

    $labelX    = $padding['left'];
    $barX      = $labelX + 94;
    $rightTextX = $barX + $barMaxWidth + 10;

    $titleY = $padding['top'] + $fontSizeTitle;
    $y      = $titleY + $titleSpacing;

    $elements = [];

    $elements[] = sprintf(
        '<text x="%d" y="%d" font-size="%d" font-family="%s" fill="%s">Most Used Languages</text>',
        $labelX,
        $titleY,
        $fontSizeTitle,
        $fontStack,
        $titleColor
    );

    foreach ($langs as $lang => $info) {
        $size  = $info['size'];
        $count = $info['count'] ?? 0;
        $pct   = $totalSize ? ($size / $totalSize) : 0;

        $barWidth = (int) round($pct * $barMaxWidth);
        $barColor = $colors[$lang] ?? '#8b949e';

        // Background bar
        $elements[] = sprintf(
            '<rect x="%d" y="%d" width="%d" height="%d" rx="4" ry="4" fill="%s"/>',
            $barX, $y, $barMaxWidth, $barHeight, $bgColor
        );

        // Progress bar
        $elements[] = sprintf(
            '<rect x="%d" y="%d" width="%d" height="%d" rx="4" ry="4" fill="%s"/>',
            $barX, $y, $barWidth, $barHeight, $barColor
        );

        // Language name
        $elements[] = sprintf(
            '<text x="%d" y="%d" font-size="%d" font-family="%s" fill="%s" dominant-baseline="middle">%s</text>',
            $labelX,
            $y + $barHeight / 2,
            $fontSizeText,
            $fontStack,
            $textColor,
            htmlspecialchars($lang)
        );

        // Percentage text
        $rightText = $count === 1
            ? sprintf("%.1f%% (1 repo)", $pct * 100)
            : sprintf("%.1f%% (%d)", $pct * 100, $count);

        $elements[] = sprintf(
            '<text x="%d" y="%d" font-size="%d" font-family="%s" fill="%s" dominant-baseline="middle">%s</text>',
            $rightTextX,
            $y + $barHeight / 2,
            $fontSizeText - 1,
            $fontStack,
            $percColor,
            $rightText
        );

        $y += $barHeight + $gap;
    }

    $svgHeight = $y + $padding['bottom'];
    $svgWidth  = $rightTextX + 60 + $padding['right'];

    $stroke = 2;
    $inset  = $stroke / 2;

    $elements[] = sprintf(
        '<rect x="%d" y="%d" width="%d" height="%d" rx="10" ry="10" fill="none" stroke="#ffffff" stroke-width="%d"/>',
        $inset,
        $inset,
        $svgWidth  - $stroke,
        $svgHeight - $stroke,
        $stroke
    );

    return sprintf(
        '<svg xmlns="http://www.w3.org/2000/svg"
              width="%d" height="%d"
              viewBox="0 0 %d %d"
              style="background:#0d1117">%s</svg>',
        $svgWidth,
        $svgHeight,
        $svgWidth,
        $svgHeight,
        implode("\n", $elements)
    );
}

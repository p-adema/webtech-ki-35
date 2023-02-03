<?php
function relative_time(string $since): string
{
    $since = strtotime($since);

    $units = ["second", "minute", "hour", "day", "week", "month", "year", "centuries"];
    $durations = ["60", "60", "24", "7", "4.35", "12", "100"];

    $current_time = time();

    $difference = $current_time - $since;
    if ($difference <= 10 && $difference >= 0) {
        return 'just now';
    }
    elseif ($difference > 0) {
        $tense = 'ago';
    } else {
        $tense = 'later';
    }

    for ($unit = 0; $difference >= $durations[$unit] && $unit < count($durations) - 1; $unit++) {
        $difference /= $durations[$unit];
    }

    $difference = round($difference);

    $period = $units[$unit] . ($difference > 1 ? 's' : '');
    return "$difference $period $tense ";
}

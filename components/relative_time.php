<?php
function time_since($old_date): string
{
    date_default_timezone_set("Europe/Amsterdam");

    $current_date = date("Y/m/d");
    $current_time = date("H:i:s");

    $current_year = intval(substr($current_date, 0, 4));
    $current_month = intval(substr($current_date, 5, 7));
    $current_day = intval(substr($current_date, 8, 10));
    $current_hour = intval(substr($current_time, 0, 2));
    $current_minute = intval(substr($current_time, 3, 5));
    $current_second = intval(substr($current_time, 6, 8));

    $old_year = intval(substr($old_date, 0, 4));
    $old_month = intval(substr($old_date, 5, 7));
    $old_day = intval(substr($old_date, 8, 10));
    $old_hour = intval(substr($old_date, 11, 13));
    $old_minute = intval(substr($old_date, 14, 16));
    $old_second = intval(substr($old_date, 17, 19));

    if ($current_year === $old_year) {
        if ($current_month === $old_month) {
            if ($current_day === $old_day) {
                if ($current_hour === $old_hour) {
                    if ($current_minute === $old_minute) {
                        $offset = $current_second - $old_second;
                        return $offset . (($offset !== 1) ? ' seconds' : ' second');
                    } else {
                        $offset = $current_minute - $old_minute;
                        return $offset . (($offset !== 1) ? ' minutes' : ' minute');
                    }
                } else {
                    $offset = $current_hour - $old_hour;
                    return $offset . (($offset !== 1) ? ' hours' : ' hour');
                }
            } else {
                $offset = $current_day - $old_day;
                return $offset . (($offset !== 1) ? ' days' : ' day');
            }
        } else {
            $offset = $current_month - $old_month;
            return $offset . (($offset !== 1) ? ' months' : ' month');
        }
    } else {
        $offset = $current_year - $old_year;
        return $offset . (($offset !== 1) ? ' years' : ' year');
    }
}

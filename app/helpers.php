<?php

use Coduo\PHPHumanizer\NumberHumanizer;


if (!function_exists('metricSuffix')) {
    function metricSuffix($value)
    {
        return NumberHumanizer::metricSuffix($value ?? 0);
    }
}

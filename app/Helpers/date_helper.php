<?php

if (!function_exists('formatCzechDate')) {
    function formatCzechDate($date)
    {
        return date("j. n. Y", strtotime($date));
    }
}
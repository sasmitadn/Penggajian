<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('user_can_access')) {
    function can_access(array $routeNames): bool
    {
        $userRoutes = json_decode(auth()->user()->category?->role ?? '[]', true);
        return !empty(array_intersect($routeNames, $userRoutes)); // get the same route
    }
}

if (!function_exists('is_active_route')) {
    function is_active_route(array $routeNames, string $class = 'active show'): string
    {
        return in_array(Route::currentRouteName(), $routeNames) ? $class : '';
    }
}

if (!function_exists('parseDate')) {
    function parseDate(?string $date, string $format = 'd M Y', string $placeholder = '-'): string
    {
        if ($date == null) {
            return $placeholder;
        } else {
            return \Carbon\Carbon::parse($date)->format($format);
        }
    }
}

if (!function_exists('parseNumber')) {
    function parseNumber(?string $data, string $placeholder = '-'): string
    {
        if ($data == null) {
            return $placeholder;
        } else {
            return fmod($data, 1) == 0 ? number_format($data, 0, '.', ',') : number_format($data, 2, '.', ',');
        }
    }
}

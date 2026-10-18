<?php
/** @var string $route  */
?>

<a
    class="nav-link {{ request()->routeIs($route) ? 'active' : '' }}"
    href="{{ route($route) }}"
    {!! request()->routeIs($route) ? 'aria-current="page"' : '' !!}
>
    {{ $slot }}
</a>
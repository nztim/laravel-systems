<?php

function local(string $key, $default = null)
{
    return app('nztim-helpers-local')->get($key, $default);
}

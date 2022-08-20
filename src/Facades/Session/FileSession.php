<?php

namespace UnknownRori\Rin\Facades\Session;

use UnknownRori\Rin\Contracts\Session;

class FileSession implements Session
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key): mixed
    {
        return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : null;
    }
}

<?php

namespace UnknownRori\Rin\Facades\Session;

use UnknownRori\Rin\Contracts\Session;

/**
 * File based session management
 */
class FileSession implements Session
{
    protected string $flash = '__flash';

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();

        $_SESSION[$this->flash] = [];
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key): mixed
    {
        return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : null;
    }

    public function session(string $key, $value = null)
    {
        if (is_null($value))
            return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : null;

        $this->set($key, $value);
    }

    public function flash(string $key, $value = null): mixed
    {
        if (is_null($value))
            return array_key_exists($key, $_SESSION[$this->flash]) ? $_SESSION[$this->flash] : null;

        $_SESSION[$this->flash] = $value;
    }
}

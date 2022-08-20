<?php

namespace UnknownRori\Rin\Contracts;

/**
 * Base interface for Session type class
 */
interface Session
{
    public function set(string $key, mixed $value);
    public function get(string $key): mixed;
}

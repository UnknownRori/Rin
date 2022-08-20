<?php

namespace UnknownRori\Rin\Contracts;

interface Session
{
    public function set(string $key, mixed $value);
    public function get(string $key): mixed;
}

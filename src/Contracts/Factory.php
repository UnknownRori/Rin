<?php

namespace UnknownRori\Rin\Contracts;

/**
 * Base interface for factory class
 */
interface Factory
{
    public static function create(string $type, array $configuration = []);
}

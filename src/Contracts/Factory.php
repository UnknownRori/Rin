<?php

namespace UnknownRori\Rin\Contracts;

interface Factory
{
    public static function create(string $type, array $configuration = []);
}

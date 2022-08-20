<?php

namespace UnknownRori\Rin\Contracts;

/**
 * Base interface for response class and it's contracts
 */
interface Response
{
    public function header(string $header, string $value): self;

    public function withHeaders(array $headers): self;

    public function cookie(
        string $name,
        mixed $value,
        int $expires = 60,
        string $path = null,
        string $domain = null,
        bool $secure = false,
        bool $httpOnly = false
    ): bool;
}

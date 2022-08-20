<?php

namespace UnknownRori\ProjectReiki\Contracts;

/**
 * Base interface for response class and it's contracts
 */
interface Response
{
    /**
     * Add header on returning response
     * @param  string $header
     * @param  string $value
     * @return self
     */
    public function header(string $header, string $value): self;

    /**
     * Add header on returning response
     * @param  string $header
     * @param  string $value
     * @return self
     */
    public function withHeaders(array $headers): self;
}

<?php

namespace UnknownRori\Rin\Contracts\Response;

use UnknownRori\Rin\Contracts\Response;

/**
 * Json type response
 */
interface JsonResponse extends Response
{
    public function json(array $data, int $httpCode): self;
}

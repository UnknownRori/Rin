<?php

namespace UnknownRori\ProjectRin\Contracts\Response;

use UnknownRori\ProjectRin\Contracts\Response;

/**
 * Json type response
 */
interface JsonResponse extends Response
{
    public function json(array $data, int $httpCode): self;
}

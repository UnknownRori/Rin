<?php

namespace UnknownRori\ProjectReiki\Contracts\Response;

use UnknownRori\ProjectReiki\Contracts\Response;

/**
 * Json type response
 */
interface JsonResponse extends Response
{
    public function json(array $data, int $httpCode): self;
}

<?php

namespace UnknownRori\ProjectReiki\Contracts\Response;

use UnknownRori\ProjectReiki\Contracts\Response;

/**
 * Json type response
 */
interface JsonResponse extends Response
{
    /**
     * Convert passed associative array into json and return it as response
     * along with passed http code
     * @param  array $data
     * @param  int   $httpCode
     * @return self
     */
    public function json(array $data, int $httpCode): self;
}

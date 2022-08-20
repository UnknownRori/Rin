<?php

namespace UnknownRori\ProjectRin\Contracts\Response;

use UnknownRori\ProjectRin\Contracts\Response;

/**
 * View type response
 */
interface ViewResponse extends Response
{
    public function view(array|string $path): self;
}

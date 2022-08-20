<?php

namespace UnknownRori\Rin\Contracts\Response;

use UnknownRori\Rin\Contracts\Response;

/**
 * View type response
 */
interface ViewResponse extends Response
{
    public function view(array|string $path): self;
}

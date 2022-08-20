<?php

namespace UnknownRori\ProjectReiki\Contracts\Response;

use UnknownRori\ProjectReiki\Contracts\Response;

/**
 * View type response
 */
interface ViewResponse extends Response
{
    public function view(array|string $path): self;
}

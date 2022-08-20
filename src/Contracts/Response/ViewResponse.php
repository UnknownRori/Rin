<?php

namespace UnknownRori\ProjectReiki\Contracts\Response;

use UnknownRori\ProjectReiki\Contracts\Response;

/**
 * View type response
 */
interface ViewResponse extends Response
{
    /**
     * Send out view response using the passed filepath that will concatenate 
     * with view path that defined when initialize the Project Reiki
     * @param  string  $path
     * @return self
     */
    public function view(string $path): self;
}

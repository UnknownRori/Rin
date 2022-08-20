<?php

namespace UnknownRori\ProjectReiki\Facades;

use SessionHandler;

class SessionManager
{
    protected SessionHandler $session;

    public function __construct()
    {
        $this->session = new SessionHandler();
    }
}

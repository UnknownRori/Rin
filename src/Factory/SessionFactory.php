<?php

namespace UnknownRori\Rin\Factory;

use UnknownRori\Rin\Contracts\{Session, Factory};
use UnknownRori\Rin\Facades\Session\FileSession;

class SessionFactory implements Factory
{
    protected static $instance = null;

    /**
     * Create Session using passed type and configuration
     * @param  string  $type
     * @param  string  $configuration
     * @return ?\UnknownRori\Rin\Contracts\Session
     */
    public static function create(string $type, array $configuration = []): ?Session
    {
        if ($type == 'file')
            self::$instance = new FileSession();

        return self::$instance;
    }
}

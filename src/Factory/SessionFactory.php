<?php

namespace UnknownRori\Rin\Factory;

use UnknownRori\Rin\Contracts\{Session, Factory};
use UnknownRori\Rin\Exceptions\DriverNotFound;
use UnknownRori\Rin\Facades\Session\FileSession;

class SessionFactory implements Factory
{
    protected static $instance = null;

    /**
     * Create Session using passed type and configuration
     * @param  string  $type
     * @param  string  $configuration
     * @return \UnknownRori\Rin\Contracts\Session
     * @throws \UnknownRori\Rin\Exceptions\DriverNotFound
     */
    public static function create(string $type, array $configuration = []): Session
    {
        if ($type == 'file')
            self::$instance = new FileSession();
        else
            throw new DriverNotFound($type);

        return self::$instance;
    }
}

<?php

namespace UnknownRori\Rin;

class Configuration
{
    // View File Location
    public string $viewLocation = './views/';

    // View File Type/Extension
    public string $viewFileType = '.php';

    // Session type driver
    public string $sessionDriver = 'file';

    // Session configuration
    public array $sessionConfig = [];

    // Allowed resource accessed on public
    public array $allowedResource = ['png', 'jpg', 'jpeg', 'gif', 'css', 'js', 'ico', 'svg', 'ttf'];
}
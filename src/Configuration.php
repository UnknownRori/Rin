<?php

namespace UnknownRori\Rin;

class Configuration
{
    // View File Location
    public ?string $viewLocation = './views/';
    // View File Type/Extension
    public ?string $viewFileType = '.php';
    public ?array $allowedResource = ['png', 'jpg', 'jpeg', 'gif', 'css', 'js', 'ico', 'svg', 'ttf'];
}
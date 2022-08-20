<?php

namespace UnknownRori\ProjectReiki\Http;

use UnknownRori\ProjectReiki\Facades\Session;

class Request
{
    protected array $GET = [];
    protected array $POST = [];
    protected string $contentType = '';
    protected string $acceptContentType = '';
    protected string $method = 'GET';
    protected string $ip = '';
    protected Session $session;


    public function __construct(protected Session $sesion)
    {
        $this->GET = &$_GET;
        $this->POST = &$_POST;
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->contentType =  array_key_exists('CONTENT_TYPE', $_SERVER) ? $_SERVER['CONTENT_TYPE'] : '';
        $this->acceptContentType = array_key_exists('HTTP_ACCEPT', $_SERVER) ? $_SERVER['HTTP_ACCEPT'] : '';
        $this->ip = $this->getClientIp();
    }

    public function __destruct()
    {
        //
    }

    /**
     * Get value of the $_GET using key if not found return null
     * or return all of them if key not passed
     * @param  string $key     = null
     * @param  mixed  $default = null
     * @return mixed
     */
    public function get(string $key = null, mixed $default = null): mixed
    {
        if (is_null($_GET))
            return $_GET;

        if (array_key_exists($key, $_GET))
            return $_GET[$key];

        return $default;
    }

    /**
     * Get value of the $_POST using key if not found return null
     * or return all of them if key not passed
     * @param  string $key     = null
     * @param  mixed  $default = null
     * @return mixed
     */
    public function post(string $key = null, mixed $default = null): mixed
    {
        if (is_null($_POST))
            return $_POST;

        if (array_key_exists($key, $_POST))
            return $_POST[$key];

        return $default;
    }

    /**
     * Get value of the $_REQUEST using key if not found return null
     * or return all of them if key not passed
     * @param  string $key     = null
     * @param  mixed  $default = null
     * @return mixed
     */
    public function all(string $key = null, mixed $default = null): mixed
    {
        if (is_null($_REQUEST))
            return $_REQUEST;

        if (array_key_exists($key, $_REQUEST))
            return $_REQUEST[$key];

        return $default;
    }

    /**
     * Get value of the $_FILES using key if not found return null
     * or return all of them if key not passed
     * @param  string $key     = null
     * @param  mixed  $default = null
     * @return mixed
     */
    public function files(string $key = null): ?array
    {
        if (is_null($key))
            return $_FILES;

        if (array_key_exists($key, $_FILES))
            return $_FILES[$key];

        return null;
    }

    /**
     * Get the session manager
     * @return \UnknownRori\ProjectReiki\Facades\Session
     */
    public function session(): Session
    {
        return $this->session;
    }

    /**
     * Get cookie using key if it's doesn't exist it will return the default value
     * if no key passed then all cookie will returned
     * @param  string               $key     = null
     * @param  mixed                $default = null
     * @return string|array|null
     */
    public function cookie(string $key = null, mixed $default = null): string|array|null
    {
        if (is_null($key))
            return $_COOKIE;

        if (array_key_exists($key, $_COOKIE))
            return $_COOKIE[$key];

        return $default;
    }

    /**
     * Check the request accepted content type using passed arguments
     * @param  string $contentType
     * @return bool
     */
    public function accepts(string $contentType): bool
    {
        return $this->acceptContentType == $contentType;
    }

    /**
     * Check if the request accept json
     * @return bool
     */
    public function acceptsJson(): bool
    {
        return $this->acceptContentType == 'application/json';
    }

    /**
     * Check if the request accept html
     * @return bool
     */
    public function acceptsHtml(): bool
    {
        return $this->acceptContentType == 'text/html';
    }

    /**
     * check if the request accept any content type
     * @return bool
     */
    public function acceptsAnyContentType(): bool
    {
        return $this->acceptContentType == '';
    }

    /**
     * Get the acceptable content type
     * @return  array
     */
    public function getAcceptableContentType(): array
    {
        return explode(',', $this->acceptContentType);
    }

    /**
     * Get the content type
     * @return  string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * Get request Bearer Token
     * @return ?string
     */
    public function bearerToken(): ?string
    {
        if (!array_key_exists('HTTP_AUTHORIZATION', $_SERVER))
            return null;

        $token = explode(' ', $_SERVER['HTTP_AUTHORIZATION']);
        $key = array_shift($token);

        if ($key == 'Bearer')
            return $token[0];

        return null;
    }

    /**
     * Get the client ip
     * @return string
     */
    protected function getClientIp()
    {
        $ipaddress = '';

        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }
}

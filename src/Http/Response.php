<?php

namespace UnknownRori\Rin\Http;

use UnknownRori\Rin\Contracts\Response as IResponse;
use UnknownRori\Rin\Contracts\Response\JsonResponse;
use UnknownRori\Rin\Contracts\Response\ViewResponse;

class Response implements IResponse, JsonResponse, ViewResponse
{
    public function __construct()
    {
        //
    }

    public function __destruct()
    {
        //
    }

    /**
     * Add header on returning response
     * @param  string $header
     * @param  string $value
     * @return self
     */
    public function header(string $header, mixed $value): self
    {
        return $this;
    }

    /**
     * Add header on returning response
     * @param  string $header
     * @param  string $value
     * @return self
     */
    public function withHeaders(array $headers): self
    {
        return $this;
    }

    /**
     * Insert cookie to the response
     * @param  string $name
     * @param  mixed  $value
     * @param  int    $expires
     * @param  string $path
     * @param  string $domain
     * @param  bool   $secure
     * @param  bool   $httpOnly
     * @return bool
     */
    public function cookie(
        string $name,
        mixed $value,
        int $expires = 60,
        ?string $path = null,
        ?string $domain = null,
        bool $secure = false,
        bool $httpOnly = false
    ): bool {
        return true;
    }

    /**
     * Send out view response using the passed filepath that will concatenate 
     * with view path that defined when initialize the Project Reiki
     * @param  string  $path
     * @return self
     */
    public function view(array|string $path): self
    {
        return $this;
    }

    /**
     * Convert passed associative array into json and return it as response
     * along with passed http code
     * @param  array $data
     * @param  int   $httpCode
     * @return self
     */
    public function json(array $data, int $httpCode): self
    {
        return $this;
    }
}

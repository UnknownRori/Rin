<?php

namespace UnknownRori\Rin\Http;

use RuntimeException;
use UnknownRori\Rin\Contracts\{Response as IResponse};
use UnknownRori\Rin\Contracts\Response\{JsonResponse, ViewResponse};
use UnknownRori\Rin\Application;

class Response implements IResponse, JsonResponse, ViewResponse
{
    protected array $data = [];
    protected array $headers = [];
    protected $response = null;
    protected array $views = [];

    public function __destruct()
    {
        // Map the header array
        array_filter(
            $this->headers, fn($value, $headerType) => header($headerType . ': ' . $value),
            ARRAY_FILTER_USE_BOTH
        );

        // Include all the view
        for ($i = 0; $i < count($this->views); $i++) {
            $this->includeView($this->views[$i], $this->data);
        }
    }

    /**
     * Add header on returning response
     * @param  string $header
     * @param  string $value
     * @return self
     */
    public function header(string $header, mixed $value): self
    {
        $this->headers[$header] = $value;
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
        $this->headers = array_merge($this->headers, $headers);
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
        ): bool
    {
        return setcookie($name, $value, $expires, $path, $domain, $secure, $httpOnly);
    }

    /**
     * Send out view response using the passed filepath that will concatenate 
     * with view path that defined when initialize the Rin
     * @param  string  $view
     * @return self
     */
    public function view(array |string $view, array $data = []): self
    {
        $this->data = $data;

        if (!is_array($view))
            $this->views[] = str_replace(".", "/", $view);
        else {
            $view = array_map(function ($value) {
                return str_replace(".", "/", $value);
            }, $view);

            array_merge($this->views, $view);
        }

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
        $this->header('Content-Type', 'application/json');
        $this->response = json_encode($data);
        return $this;
    }

    protected function includeView(string $path, array $data = [])
    {
        extract($data);

        $path = (isset(Application::$config->viewLocation) ?Application::$config->viewLocation : './views//')
            . $path . (isset(Application::$config->viewFileType) ?Application::$config->viewFileType : '.php');

        if (!file_exists($path))
            throw new RuntimeException("File not found! File : {$path}");

        require $path;
    }
}

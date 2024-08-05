<?php

namespace Core;

class Response
{
    public function __construct(
        private ?string $content = "",
        private int $statusCode = 200,
        private array $headers = [],
    )
    {

    }
    public function send(): void
    {
        if (!empty($this->headers)){
            foreach ($this->headers as $key => $value){
                $header = "{$key}: {$value}";
                header($header);
            }
        }
        http_response_code($this->statusCode);
        echo  $this->content;
    }

    public function setContent(string $content): Response
    {
        $this->content = $content;
        return $this;

    }

    public function setStatusCode(int $statusCode): Response
    {
        $this->statusCode = $statusCode;
        return $this;

    }

    public function setHeaders(array $headers): Response
    {
        $this->headers = $headers;
        return $this;

    }

    public function json($data): Response
    {
        $this->content = json_encode($data);
        $this->setHeaders(['Content-Type' => 'application/json']);
        return $this;
    }

}
<?php

namespace App\Arch\Domain\Response;

class BaseResponse {

    private string $message = 'Mensaje por defecto';
    private mixed $data = [];

    public function __construct() {

    }

    /**
     * @return string
     */
    public function getMessage(): string {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getData(): mixed {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData(mixed $data): void {
        $this->data = $data;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void {
        $this->message = $message;
    }

}

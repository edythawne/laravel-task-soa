<?php

namespace App\Arch\Domain\UseCase;

use App\Arch\Domain\Response\BaseResponse;
use Illuminate\Http\Request;

abstract class BaseCase {

    private ?array $attributes;
    private BaseResponse $response;

    public function __construct() {
        $this -> response = new BaseResponse();
    }

    public function withRequest(Request $request) : BaseCase {
        $this -> attributes = $request -> all();
        return $this;
    }

    public function getAttributes(): ?array {
        return $this->attributes;
    }

    public function setResponse(string $message, mixed $data) : void {
        $this -> response -> setMessage($message);
        $this -> response -> setData($data);
    }

    public function toResponse() : BaseResponse {
        return $this -> response;
    }

    abstract public function build() : BaseCase;


}


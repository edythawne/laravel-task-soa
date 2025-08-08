<?php

namespace App\Arch\Domain\UseCase;

interface BaseIterator {

    public function transform() : array;

    public function feedback(mixed $response);

}

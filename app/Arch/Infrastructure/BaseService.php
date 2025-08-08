<?php

namespace App\Arch\Infrastructure;

use App\Arch\Domain\UseCase\BaseIterator;

abstract class BaseService {

    public BaseIterator $iterator;

    public function __construct(BaseIterator $iterator) {
        $this -> iterator = $iterator;
    }

    abstract public function execute();

}

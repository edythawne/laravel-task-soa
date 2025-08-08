<?php

namespace App\Arch\Domain\UseCase\Auth;

use App\Arch\Domain\UseCase\BaseCase;
use App\Arch\Domain\UseCase\BaseIterator;
use App\Arch\Infrastructure\App\SyncTaskService;
use App\Arch\Infrastructure\Auth\LoginUserService;
use App\Arch\Infrastructure\Auth\UserCreatorService;
use App\Arch\Util\StringUtil;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RegisterCase extends BaseCase implements BaseIterator {

    private UserCreatorService $service;

    /**
     * Constructor que inicializa el servicio
     */
    public function __construct() {
        parent::__construct();
        $this -> service = new UserCreatorService($this);
    }

    /**
     * Metodo que permite aplicar filtro y/o limpieza a los datos de la response
     * @return array
     */
    public function transform(): array {
        return [
            'name'  =>  Str::trim(Arr::get($this -> getAttributes(), 'name')),
            'first_surname'  =>  Str::trim(Arr::get($this -> getAttributes(), 'first_surname')),
            'second_surname'  =>  Str::trim(Arr::get($this -> getAttributes(), 'second_surname')),
            'phone'  =>  Arr::get($this -> getAttributes(), 'phone'),
            'email'  =>  Str::trim(Arr::get($this -> getAttributes(), 'email')),
            'password'  =>  hash('sha256', Arr::get($this -> getAttributes(), 'password')),
        ];
    }

    /**
     * Metodo de comunicacion entre el llamado del caso de uso y el servicio
     * Aqui se llama al metodo execute del servicio que a su vez usa a transform & feedback como observador
     * @return BaseCase
     * @throws \Throwable
     */
    public function build(): BaseCase {
        $this -> service -> execute();
        return $this;
    }

    public function feedback(mixed $response) : void {
        $this -> setResponse("OK", $response);
    }
}

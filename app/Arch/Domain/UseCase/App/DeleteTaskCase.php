<?php

namespace App\Arch\Domain\UseCase\App;

use App\Arch\Domain\UseCase\BaseCase;
use App\Arch\Domain\UseCase\BaseIterator;
use App\Arch\Infrastructure\App\DeleteTaskService;
use App\Arch\Infrastructure\App\GetAllTaskService;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class DeleteTaskCase extends BaseCase implements BaseIterator {

    private DeleteTaskService $service;

    /**
     * Constructor que inicializa el servicio
     */
    public function __construct() {
        parent::__construct();
        $this -> service = new DeleteTaskService($this);
    }

    /**
     * Metodo que permite aplicar filtro y/o limpieza a los datos de la response
     * @return array
     */
    public function transform(): array {
        return [
            'id' => Arr::get($this -> getAttributes(), 'id'),
        ];
    }

    /**
     * Metodo de comunicacion entre el llamado del caso de uso y el servicio
     * Aqui se llama al metodo execute del servicio que a su vez usa a transform & feedback como observador
     * @return BaseCase
     */
    public function build(): BaseCase {
        $this -> service -> execute();
        return $this;
    }

    public function feedback(mixed $response) : void {
        $this -> setResponse("Ok", $response);
    }
}

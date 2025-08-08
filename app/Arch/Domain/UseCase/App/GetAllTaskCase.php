<?php

namespace App\Arch\Domain\UseCase\App;

use App\Arch\Domain\UseCase\BaseCase;
use App\Arch\Domain\UseCase\BaseIterator;
use App\Arch\Infrastructure\App\GetAllTaskService;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class GetAllTaskCase extends BaseCase implements BaseIterator {

    private GetAllTaskService $service;

    /**
     * Constructor que inicializa el servicio
     */
    public function __construct() {
        parent::__construct();
        $this -> service = new GetAllTaskService($this);
    }

    /**
     * Metodo que permite aplicar filtro y/o limpieza a los datos de la response
     * @return array
     */
    public function transform(): array {
        if (Arr::exists($this -> getAttributes(), 'search')) {
            return [
                'name' => Str::trim(Arr::get($this -> getAttributes(), 'search')),
            ];
        }

        return [];
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
        $message = is_array($response) && count($response) > 0 ? 'Ok' : 'Sin resultados';
        $this -> setResponse($message, $response);
    }
}

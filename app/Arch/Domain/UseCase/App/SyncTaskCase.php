<?php

namespace App\Arch\Domain\UseCase\App;

use App\Arch\Domain\UseCase\BaseCase;
use App\Arch\Domain\UseCase\BaseIterator;
use App\Arch\Infrastructure\App\SyncTaskService;
use App\Arch\Util\StringUtil;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SyncTaskCase extends BaseCase implements BaseIterator {

    private SyncTaskService $service;

    /**
     * Constructor que inicializa el servicio
     */
    public function __construct() {
        parent::__construct();
        $this -> service = new SyncTaskService($this);
    }

    /**
     * Metodo que permite aplicar filtro y/o limpieza a los datos de la response
     * @return array
     */
    public function transform(): array {
        $name = Str::trim(Arr::get($this -> getAttributes(), 'name'));
        $name_regex = StringUtil::of($name) -> clearSpecialChars() -> lower() -> slug("_") -> toString();

        return [
            'is_created' => Arr::get($this -> getAttributes(), 'is_created'),
            'status_id'  =>  Arr::get($this -> getAttributes(), 'status_key'),
            'name'  =>  $name,
            'name_regex'  =>  $name_regex,
            'description'  =>  Arr::get($this -> getAttributes(), 'description'),
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

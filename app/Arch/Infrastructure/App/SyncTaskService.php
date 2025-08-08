<?php

namespace App\Arch\Infrastructure\App;

use App\Arch\Infrastructure\BaseService;
use App\Models\App\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SyncTaskService extends BaseService {

    /**
     * Creacion de un registro en la base de datos
     * @return void
     * @throws \Throwable
     */
    public function execute() {
        try {
            $data = $this -> iterator -> transform();
            $isCreate = Arr::pull($data, 'is_created');


            Log::info($data);
            Log::info($isCreate);

            DB::beginTransaction();

            // Crear
            if ($isCreate) {
                // Crear un registro
                // Dejando la responsabilidad de verificar el id del estatus & name_regex a la base de datos
                $isCreated = Task::create($data);

                if ($isCreated) {
                    DB::commit();
                    $this -> iterator -> feedback(true);
                    return;
                }
            }

            // Actualizar
            if (!$isCreate) {
                $name_regex =  Arr::pull($data, 'name_regex');
                $isUpdated = Task::where('name_regex', $name_regex) -> update($data);

                if ($isUpdated) {
                    DB::commit();
                    $this -> iterator -> feedback(true);
                    return;
                }
            }

            throw new ModelNotFoundException("Se ha producido un error al intentar crear el registro");
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error($ex -> getMessage());
            throw new ModelNotFoundException($ex);
        }
    }

}


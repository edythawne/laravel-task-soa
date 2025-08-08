<?php

namespace App\Arch\Infrastructure\Auth;

use App\Arch\Infrastructure\BaseService;
use App\Models\App\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LoginUserService extends BaseService {

    /**
     * Creacion de un registro en la base de datos
     * @return void
     * @throws \Throwable
     */
    public function execute() {
        try {
            $data = $this -> iterator -> transform();
            Log::info($data);

            DB::beginTransaction();

            $user = User::where($data) -> first();
            // No se manejaran roles, no son importantes para el ejercicio

            // Generación de token
            $token = $user -> createToken('auth') -> plainTextToken;

            DB::commit();

            $this -> iterator -> feedback($token);
        } catch (\Exception $ex) {
            DB::rollBack();
            LOG::error($ex -> getMessage());
            $this -> iterator -> feedback(null);
        }
    }

}


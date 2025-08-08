<?php

namespace App\Arch\Infrastructure\App;

use App\Arch\Infrastructure\BaseService;
use App\Models\App\Task;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DeleteTaskService extends BaseService {

    public function execute() {
        $idTask = Arr::get($this -> iterator -> transform(), 'id');
        Log::info($idTask);

        // ELiminar registro por id
        $isDeleted = Task::where("id", $idTask) -> delete();
        $this -> iterator -> feedback($isDeleted);
    }

}


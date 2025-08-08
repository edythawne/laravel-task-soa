<?php

namespace App\Arch\Infrastructure\App;

use App\Arch\Infrastructure\BaseService;
use App\Models\App\Task;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class GetAllTaskService extends BaseService {

    public function execute() {
        $data = $this -> iterator -> transform();

        // Si existe filtro por nombre
        $search = Arr::get($data, 'name', '');

        $query = Task::with('status') -> where('active', true);

        // Aplicar filtro de busqueda
        if (!empty($search)){
            $query -> where('name', 'ilike', '%'.$search.'%')
                -> orWhere('description', 'ilike', '%'.$search.'%');
        }

        $query = $query -> select([
            'id',
            'status_id',
            'name',
            'description',
            'active',
            'created_at',
            'created_by',
        ]) -> orderBy('name') -> get() -> toArray();

        $this -> iterator -> feedback($query);
    }

}


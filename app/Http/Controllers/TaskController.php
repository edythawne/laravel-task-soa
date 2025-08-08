<?php

namespace App\Http\Controllers;

use App\Arch\Domain\UseCase\App\DeleteTaskCase;
use App\Arch\Domain\UseCase\App\GetAllTaskCase;
use App\Arch\Domain\UseCase\App\SyncTaskCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller {

    /**
     * Obtiene la lista de tareas actuales
     * @param Request $request
     * @param GetAllTaskCase $case
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, GetAllTaskCase $case) {
        $this -> applyRules($request, [
            'search' => 'string|nullable|sometimes',
        ]);

        return $this -> getResponse($case -> withRequest($request) -> build() -> toResponse());
    }

    /**
     * Crear una tarea
     * @param Request $request
     * @param SyncTaskCase $case
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(Request $request, SyncTaskCase $case) {
        $this -> applyRules($request, [
            'status_key' => 'required|numeric',
            'name' => 'string|required',
            'description' => 'required|string',
        ]);

        // Se agrega nueva key como bandera de actualización
        $request -> merge(['is_created' => true]);
        return $this -> getResponse($case -> withRequest($request) -> build() -> toResponse());
    }

    /**
     * Actualizar una tarea
     * @param Request $request
     * @param SyncTaskCase $case
     * @return JsonResponse
     * @throws \Throwable
     */
    public function update(Request $request, SyncTaskCase $case) {
        $this -> applyRules($request, [
            'status_key' => 'required|numeric',
            'name' => 'string|required',
            'description' => 'required|string',
        ]);

        // Se agrega nueva key como bandera de actualización
        $request -> merge(['is_created' => false]);
        return $this -> getResponse($case -> withRequest($request) -> build() -> toResponse());
    }

    /**
     * Eliminar tarea
     * @param Request $request
     * @param int $id
     * @param DeleteTaskCase $case
     * @return JsonResponse
     */
    public function delete(Request $request, int $id, DeleteTaskCase $case) {
        $request -> merge(['id' => $id]);
        return $this -> getResponse($case -> withRequest($request) -> build() -> toResponse());
    }

}


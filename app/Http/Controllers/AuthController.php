<?php

namespace App\Http\Controllers;

use App\Arch\Domain\UseCase\App\DeleteTaskCase;
use App\Arch\Domain\UseCase\App\GetAllTaskCase;
use App\Arch\Domain\UseCase\App\SyncTaskCase;
use App\Arch\Domain\UseCase\Auth\LoginCase;
use App\Arch\Domain\UseCase\Auth\RegisterCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller {

    /**
     * Login
     * @throws \Throwable
     */
    public function login(Request $request, LoginCase $case) {
        $this -> applyRules($request, [
            'email' => 'email|required',
            'password' => 'required|string',
        ]);

        return $this -> getResponse($case -> withRequest($request) -> build() -> toResponse());
    }

    /**
     * Login
     * @throws \Throwable
     */
    public function register(Request $request, RegisterCase $case) {
        $this -> applyRules($request, [
            'name' => 'string|required',
            'first_surname' => 'string|required',
            'second_surname' => 'string|required',
            'phone' => 'string|nullable',
            'email' => 'email|required',
            'password' => 'required|string',
        ]);

        return $this -> getResponse($case -> withRequest($request) -> build() -> toResponse());
    }


}


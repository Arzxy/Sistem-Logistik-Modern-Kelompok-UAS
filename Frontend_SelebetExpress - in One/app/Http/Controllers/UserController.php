<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $users = $this->userService->getAllUsers();

        return view(
            'dashboard.users.index',
            compact('users')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $warehouses = $this->userService->getWarehouses();
        return view('dashboard.users.create', compact('warehouses'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        return $this->userService
            ->storeUser($request);
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $user = $this->userService
            ->getUserById($id);

        return view(
            'dashboard.users.show',
            compact('user')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $user       = $this->userService->getUserById($id);
        $warehouses = $this->userService->getWarehouses();
        $courier    = $this->userService->getCourierByUserId($id);

        return view(
            'dashboard.users.edit',
            compact('user', 'warehouses', 'courier')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        return $this->userService
            ->updateUser($request, $id);
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        return $this->userService
            ->deleteUser($id);
    }
}
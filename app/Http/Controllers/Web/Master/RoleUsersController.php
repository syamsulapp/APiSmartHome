<?php

namespace App\Http\Controllers\Web\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\FiturService\masterData\RoleUsersRepository;

class RoleUsersController extends Controller
{
    protected $roleRepository;

    public function __construct(RoleUsersRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function index(Request $index)
    {
        return $this->roleRepository->index($index);
    }

    public function show($id)
    {
        return $this->roleRepository->show($id);
    }

    public function store(Request $store)
    {
        return $this->roleRepository->store($store);
    }

    public function update(Request $update)
    {
        return $this->roleRepository->update($update);
    }

    public function delete(Request $delete)
    {
        return $this->roleRepository->delete($delete);
    }
}

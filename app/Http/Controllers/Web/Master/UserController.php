<?php

namespace App\Http\Controllers\Web\Master;

use App\Http\Controllers\Controller;
use App\Repositories\FiturService\masterData\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $repoUsers;

    public function __construct(UserRepository $repoUsers)
    {
        $this->repoUsers = $repoUsers;
    }

    public function index(Request $index)
    {
        return $this->repoUsers->index($index);
    }
    public function show($id)
    {
        return $this->repoUsers->show($id);
    }
    public function store(Request $store)
    {
        return $this->repoUsers->store($store);
    }
    public function update(Request $update)
    {
        return $this->repoUsers->update($update);
    }
    public function delete(Request $delete)
    {
        return $this->repoUsers->delete($delete);
    }
}

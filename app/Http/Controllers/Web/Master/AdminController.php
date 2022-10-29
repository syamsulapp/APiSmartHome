<?php

namespace App\Http\Controllers\Web\Master;

use App\Http\Controllers\Controller;
use App\Repositories\FiturService\masterData\AdminRepository;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $repoAdmin;

    public function __construct(AdminRepository $repoAdmin)
    {
        $this->repoAdmin = $repoAdmin;
    }

    public function index(Request $index)
    {
        return $this->repoAdmin->index($index);
    }
    public function show($id)
    {
        return $this->repoAdmin->show($id);
    }
    public function store(Request $store)
    {
        return $this->repoAdmin->store($store);
    }
    public function update(Request $update)
    {
        return $this->repoAdmin->update($update);
    }
    public function delete(Request $delete)
    {
        return $this->repoAdmin->delete($delete);
    }
}

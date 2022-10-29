<?php

namespace App\Http\Controllers\Web\Master\Platform;

use App\Repositories\FiturService\masterData\PlatformVersionRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    protected $platformVersionRepo;

    public function __construct(PlatformVersionRepository $platformVersionRepo)
    {
        $this->platformVersionRepo = $platformVersionRepo;
    }

    public function index(Request $index)
    {
        return $this->platformVersionRepo->index($index);
    }

    public function show($id)
    {
        return $this->platformVersionRepo->show($id);
    }
    public function store(Request $store)
    {
        return $this->platformVersionRepo->store($store);
    }
    public function update(Request $update)
    {
        return $this->platformVersionRepo->update($update);
    }
    public function delete(Request $delete)
    {
        return $this->platformVersionRepo->delete($delete);
    }
}

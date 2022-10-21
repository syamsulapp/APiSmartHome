<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\Web\Auth\WebAuthRepository;
use Illuminate\Http\Request;

class WebAuthController extends Controller
{
    protected $webAuth;


    public function __construct(WebAuthRepository $webAuth)
    {
        $this->webAuth = $webAuth;
    }


    public function login(Request $login)
    {
    }

    public function register(Request $register)
    {
    }
}

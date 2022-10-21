<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\Web\Auth\WebAuthRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WebAuthController extends Controller
{
    // property for web auth repository
    protected $webAuth;

    public function __construct(WebAuthRepository $webAuth)
    {
        $this->webAuth = $webAuth;
    }


    public function login(Request $login)
    {

        return $this->webAuth->login($login);
    }

    public function register(Request $register)
    {
        return $this->webAuth->register($register);
    }

    public function logout(Request $logout)
    {
        return $this->webAuth->logout($logout);
    }
}

<?php

namespace App\Http\Controllers;

use App\Repositories\User\ForgotPasswordRepository;
use App\Repositories\User\LoginRepository;
use App\Repositories\User\LogoutRepository;
use App\Repositories\User\RegisterRepository;
use Laravel\Lumen\Routing\Controller as Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(LoginRepository $login, RegisterRepository $register, LogoutRepository $logout, ForgotPasswordRepository $forgot)
    {
        $this->loginRepo = $login;
        $this->registerRepo = $register;
        $this->logoutRepo = $logout;
        $this->forgotPass = $forgot;
    }
    public function login(Request $login)
    {
        return $this->loginRepo->login($login);
    }

    public function register(Request $register)
    {
        return $this->registerRepo->register($register);
    }

    public function logout(Request $logout)
    {
        $this->logoutRepo->logout($logout);
    }

    public function forgotPass(Request $forgot)
    {
        $this->forgotPass->forgotPassword($forgot);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\JsonBuilder\ReturnResponse;
use App\Models\User;
use App\Repositories\User\ForgotPasswordRepository;
use App\Repositories\User\LoginRepository;
use App\Repositories\User\LogoutRepository;
use App\Repositories\User\Profile\ProfileRepository;
use App\Repositories\User\RegisterRepository;
use Laravel\Lumen\Routing\Controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct(User $user, ReturnResponse $respon, LoginRepository $login, RegisterRepository $register, LogoutRepository $logout, ForgotPasswordRepository $forgot, ProfileRepository $profile)
    {
        $this->loginRepo = $login;
        $this->registerRepo = $register;
        $this->logoutRepo = $logout;
        $this->forgotPass = $forgot;
        $this->profile = $profile;
        $this->respon = $respon;
        $this->user = $user;
    }
    public function login(Request $login)
    {
        return $this->loginRepo->login($login, $this->respon, $this->user);
    }

    public function register(Request $register)
    {
        return $this->registerRepo->register($register, $this->user, $this->respon);
    }

    public function logout(Request $logout)
    {
        return $this->logoutRepo->logout($logout, $this->user, $this->respon);
    }

    public function forgotPass(Request $forgot)
    {
        $this->forgotPass->forgotPassword($forgot);
    }

    public function profile(Request $profile)
    {
        return $this->profile->profile($profile, $this->user, $this->respon);
    }

    public function update_profile(Request $update_profile)
    {
        return $this->profile->update_profile($update_profile, $this->respon, $this->user);
    }
}

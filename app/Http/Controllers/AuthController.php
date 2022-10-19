<?php

namespace App\Http\Controllers;

use App\Http\JsonBuilder\ReturnResponse;
use App\Models\ModelsRole;
use App\Models\User;
use App\Repositories\User\ForgotPasswordRepository;
use App\Repositories\User\LoginRepository;
use App\Repositories\User\LogoutRepository;
use App\Repositories\User\Profile\ProfileRepository;
use App\Repositories\User\RegisterRepository;
use Laravel\Lumen\Routing\Controller as Controller;
use Illuminate\Http\Request;


class AuthController extends Controller
{

    public function __construct(ModelsRole $role, LoginRepository $loginRepo, RegisterRepository $registerRepository, LogoutRepository $logoutRepository, ForgotPasswordRepository $forgotRepository, ProfileRepository $profileRepository)
    {
        $this->loginRepo = $loginRepo;
        $this->registerRepo = $registerRepository;
        $this->logoutRepo = $logoutRepository;
        $this->forgotPass = $forgotRepository;
        $this->profile = $profileRepository;
        $this->role = $role;
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
        return $this->logoutRepo->logout($logout);
    }

    public function forgotPass(Request $forgot)
    {
        $this->forgotPass->forgotPassword($forgot);
    }

    public function profile(Request $profile)
    {
        return $this->profile->profile($profile, $this->role);
    }

    public function update_profile(Request $update_profile)
    {
        return $this->profile->update_profile($update_profile);
    }
}

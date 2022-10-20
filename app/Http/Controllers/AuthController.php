<?php

namespace App\Http\Controllers;

use App\Repositories\User\LoginRepository;
use App\Repositories\User\LogoutRepository;
use App\Repositories\User\Profile\ProfileRepository;
use App\Repositories\User\RegisterRepository;
use Laravel\Lumen\Routing\Controller as Controller;
use Illuminate\Http\Request;


class AuthController extends Controller
{

    public function __construct(LoginRepository $loginRepo, RegisterRepository $registerRepository, LogoutRepository $logoutRepository, ProfileRepository $profileRepository)
    {
        $this->loginRepo = $loginRepo;
        $this->registerRepo = $registerRepository;
        $this->logoutRepo = $logoutRepository;
        $this->profile = $profileRepository;
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

    public function profile(Request $profile)
    {
        return $this->profile->profile($profile);
    }

    public function update_profile(Request $update_profile)
    {
        return $this->profile->update_profile($update_profile);
    }
}

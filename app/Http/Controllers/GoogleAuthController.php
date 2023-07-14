<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function create()
    {

        try {
            //code...
            $google_user = Socialite::driver('google')->user();
            $user = User::where('google_id', $google_user->getId())->first();

            if (!$user) {
                $new_user = new User();
                $new_user->name = $google_user->getName();
                $new_user->email = $google_user->getEmail();
                $new_user->google_id = $google_user->getId();
                $new_user->save();

                Auth::login($new_user);

                return redirect()->intended('dashboard');
            } else {
                Auth::login($user);
                return redirect()->intended('dashboard');
            }

        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
<?php

namespace App\Http\Controllers;

use Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GoogleController extends Controller
{
    public function redirect(){
        return Socialite::driver('google')->redirect();
    }

    public function callback(){

        $googleUser = Socialite::driver('google')->stateless()->user();

        $eUser = User::where('email', $googleUser->email)->first(); // laravel로 회원가입을 했을 경우
        $gUser = User::where('google_id', $googleUser->id)->first(); // google_id가 있을 경우

        if($eUser){
            if($gUser){
                Auth::login($eUser);
                return redirect('/');
            }else{

                $eUser->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                ]);
                Auth::login($eUser);
                return redirect('/');
            }
            
        }else{

            // $googleUser에서 avatar도 받아올 수 있다.
            $newUser = User::Create([
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(Str::random(24)), //24자 랜덤 비밀번호를 주세요.
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
            ]);
            //로그인 처리
            Auth::login($newUser);

            return redirect('/');
        }
        
    }
}

<?php

namespace App\Http\Controllers;

use Jenssegers\Agent\Agent;
use App\Models\New_Customer;
use Illuminate\Http\Request;
use App\Enum\Social\SocialEnum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function facebookRedirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function loginWithFacebook()
    {
        $user = Socialite::driver('facebook')->user();
        $social = SocialEnum::Facebook;
        $isUser = New_Customer::where('socialite',$social)->first();
        if($isUser)
        {
            Auth::login($isUser);
        }else{
            $createUser = New_Customer::create([
                'email'=>$user->email,
                'name'=>$user->name
                'user_id'=>$user->id,
                'socialite'=>$social,
                'password'=>encrypt('admin123'),
            ]);

            Auth::login($createUser);
            return redirect()->back()->withErrors('error')->withInput();
        }

    }

    public function redirectToGoogle()
    {
    return Socialite::driver('google')->redirect();
    }

    public function loginWithGoogle()
    {      
        $agent = new Agent();
        $device = $agent->device();
        $platform = $agent->platform();
        $browser = $agent->browser();
        $desktop = $agent->isDesktop();
        $agent->isRobot();

        $info = [
            'Device'=>$device,
            'Platform'=>$platform,
            'Browser'=>$browser,
            'Desktop'=>$desktop,            
        ];
        $collect = collect($info);
        $user = Socialite::driver('google')->user();
        $social = SocialEnum::Google;   
        $isUser = New_Customer::where('socialite',$social)->first();
        if($isUser)
        {
            Auth::login($isUser);
            return redirect()->route('index');

        }else{
            $createUser = New_Customer::updateOrCreate([
                'email'=>$user->email,
                'name'=>$user->name,
                'user_id'=>$user->id,
                'socialite'=>$social,
                'password'=>encrypt('admin123'),
            ]);
            Auth::login($createUser);
            return redirect()->back()->with('Loged in Successfully');
        }
    }
    public function githubRedirect()
    {
        return Socialite::driver('github')->redirect();
    }
    
    public function loginWithGithub()
    {
        $agent = new Agent();
        $device = $agent->device();
        $platform = $agent->platform();
        $browser = $agent->browser();
        $desktop = $agent->isDesktop();
        $agent->isRobot();

        $info = [
            'Device'=>$device,
            'Platform'=>$platform,
            'Browser'=>$browser,
            'Desktop'=>$desktop,            
        ];
        $collect = collect($info);
        $user = Socialite::driver('github')->user();
        $social = SocialEnum::Github;
        $isUser = New_Customer::where('socialite',$social)->first();
        if($isUser)
        {
            Auth::login($isUser);
            return redirect()->route('index');
        }else{
            $createUser = New_Customer::updateOrCreate([
                'email'=>$user->email,
                'name'=>$user->name,
                'socialite'=>$social,
                'user_id' =>$user->id,
                'password'=>encrypt('admin123'),
            ]);
            Auth::login($createUser);
            return redirect()->back()->with('Loged in Successfully');
        }
    }

}

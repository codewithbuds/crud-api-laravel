<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserVerify;
use Session;
use Hash;
use Mail;
use Illuminate\Support\Str;

class AuthController extends Controller

{
    //Redirects to the login
    public function index(){
        return view('auth.login');
    }
    //
    public function registration(){
        return view('auth.registration');
    }

    // for user login
    public function postLogin(UserRequest $request)
    {
        $request->validated();

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard')
            ->withSuccess('You have Successfully loggedin');
        }

        return redirect("login")->withSuccess('Oppes! You have entered invalid credentials');
    }

    //for user registeration at signup
    public function postRegistration(UserRequest $request)
    {
        $request->validated();

        $data = $request->all();
        $createUser = $this->create($data);
        $token = Str::random(255);

        UserVerify::create([
            'user_id' => $createUser->id,
            'token' => $token,
        ]);

        // Here I can send mail through Queues or like this 
        Mail::send(
            'email.emailVerificationEmail',
            ['token' => $token],
            function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Email Verification Mail');
            }
        );

        return redirect("dashboard")->withSuccess('Great! You have Successfully loggedin');

    }
    //Shows the Dashboard
    public function dashboard()
    {
        if(Auth::check()){
            return view('dashboard');
        }
  
        return redirect("login")->withSuccess('Opps! You do not have access');
    }

        // Saves the user data
    public function create(array $data)
    {
        return User::create([
            'fname' => $data['fname'],
            'lname' => $data['lname'],
            'email' => $data['email'],
            'profileimage' => $data['profileimage'],
            'password' => Hash::make($data['password']),
        ]);
        
    }
    public function logout() {
        Session::flush();
        Auth::logout();
  
        return Redirect('login');
    }
    //Verifies the account 
    public function verifyAccount($token)
    {
        $verifyUser = UserVerify::where('token', $token)->first();

        $message = 'Sorry your email cannot be identified.';

        if (!is_null($verifyUser)) {
            $user = $verifyUser->user;

            if (!$user->is_email_verified) {
                $verifyUser->user->is_email_verified = 1;
                $verifyUser->user->save();
                $message = 'Your e-mail is verified. You can now login.';
            } else {
                $message =
                    'Your e-mail is already verified. You can now login.';
            }
        }
        return redirect()
            ->route('login')
            ->with('message', $message);
    }
}
// Registers the user
// public function save_user(UserRequest $request)
// {
//     $user = User::where('email', $request['email'])->first();
//     if ($user){
//         return response()->json(['exists' => 'Email already exist']);
//     }
//     else{
//         $user = new user;
//         $user->fname = $request['fname'];
//         $user->lname = $request['lname'];
//         $user->email = $request['email'];
//         $user->password = bcrypt($request['password']);
//         $user->profileimage = $request['profileimage'];
//     }
//         $request->validated();
//         $user->save();
//         return response()->json(['success'=>'User registered successfully']);

// }



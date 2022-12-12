<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Mail;
use Hash;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    //Forgot the password
    public function submitForgetPasswordForm(UserRequest $request)
    {
        $request->validated();

        $token = Str::random(255);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);
         // Here I can send mail through Queues or like this 
        Mail::send('email.forgetPassword', ['token' => $token], function (
            $message
        ) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return back()->with(
            'message',
            'We have e-mailed your password reset link!'
        );
    }
    //For Reset password
    public function submitResetPasswordForm(UserRequest $request)
    {
        $request->validated();

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token,
            ])
            ->first();

        if (!$updatePassword) {
            return back()
                ->withInput()
                ->with('error', 'Invalid token!');
        }

        $user = User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_resets')
            ->where(['email' => $request->email])
            ->delete();

        return with(
            'message',
            'Your password has been changed!'
        );
    }
}

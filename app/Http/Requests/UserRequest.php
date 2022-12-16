<?php

namespace App\Http\Requests;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        
        return match($this->method()){
            'POST' => $this->Registration(),
            'POST' =>$this->Login(),
            'POST' =>$this->ForgetPassword(),
            'POST' =>$this->ResetPassword(),
        };
    }
        //validation for postRegistration
       public function Registration(): array 
        {
            return [
                'fname' =>' required|max:255',
                'lname' =>' required|max:255',
                'email' =>' required|email|unique:users',
                'password' => 'required|min:8',
            ];
        }
        
        
        //validation for postlogin
        public function Login(): array {
        return [
            'email' => 'required',
            'password' => 'required',
        ];
    }
    // validation for Forgot Password
     public function  ForgetPassword(): array 
    {
        return [
            'email' => 'required|email|exists:users',
        ];
    }
     // validation for Reset Password
    public function ResetPassword(): array{
        return[
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ];
     }
     
    public function messages()
    {
        return [
            'fname.required' => 'Please Enter your First Name',
            'lname.required' => "Please Enter your Last name",
            'email.required' => 'Please Enter your Email',
            'password.required' => 'Enter Your Password',
        ];
    }
}

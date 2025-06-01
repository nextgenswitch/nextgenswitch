<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    //use SendsPasswordResetEmails;
    

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);
        
        $user = User::where('email', $request->email)->first();

        if(!$user){
            return redirect()->route('login')
                ->with('error_message', __('The email is not associated with your account.'));
        }

        $token = Password::getRepository()->create($user);
        $resetLink = url('password/reset/' . $token);
        $data = [
            'name' => $user->name,
            'link' => $resetLink
        ];

        Mail::send($request->email, 'Password Reset', $data, 'reset_password');

        return redirect()->route('login')
                ->with('success_message', __('Password reset link has been sent to your email address. Kindly check your inbox.'));
    }


    /**
     * Validate the email for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
    }


}

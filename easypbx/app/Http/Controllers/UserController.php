<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Plan;
use App\Models\User;
use App\Rules\MatchOldPass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        $user = User::find(auth()->id());

        return view('users.profile', compact('user'));
    }

    public function update(Request $request){

        $data = $request->validate([
            'user.name' => ['required', 'string'],
            'user.email' => ['required', 'email'],
            // 'organization.domain' => ['required', 'string'],
            // 'organization.email' => ['required', 'email'],
            // 'organization.contact_no' => ['required', 'string'],
            // 'organization.address' => ['required', 'string']
        ]);
        
        $user = User::find(auth()->id());
        $user->update($data['user']);

        if($user){
            return back()->with('success_message', 'User profile updated successfully');
        }
            

        return back()->withInput()->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to update profile.']);

    }

    public function showChangePasswordForm(){
        return view('users.change_password');
    }

    public function changePassword(Request $request){
        $user = User::find(auth()->id());

        $request->validate([
            'current_password' => ['required', new MatchOldPass($user)],
            'password' => ['required', 'min:8', 'max:20', 'confirmed']
        ]);

        $user = $user->update(['password' => Hash::make($request->password)]);

        if($user)
            return back()->with('success_message', 'Password change successfully');


        return back()->withInput()->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to change password.']);
        
    }
}

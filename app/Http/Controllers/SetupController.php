<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SetupController extends Controller
{

    /**
     * Show the setup form.
     *
     * @return \Illuminate\View\View
     */
    public function showForm()
    {
        if (Organization::exists() || User::exists()) {
            return redirect()->route('login')->with('error_message', 'Setup already completed. Please log in.');
        }

        return view('setup.form');
    }

    /**
     * Handle the setup form submission.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleForm(Request $request)
    {
        if (Organization::exists() || User::exists()) {
            return redirect()->route('login')->with('error_message', 'Setup already completed. Please log in.');
        }

        $data = $request->validate([
            'org_name' => 'required|string|max:255',
            'domain' => ['required', 'regex:/^(?!:\/\/)([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$/'],
            'contact' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
            'address' => 'nullable|string|max:255',
        ]);

        // Here you would typically save the data to the database or perform other actions.
        try {
            Artisan::call('easypbx:setup', [
                '--org' => $data['org_name'],
                '--domain' => $data['domain'],
                '--contact' => $data['contact'],
                '--email' => $data['email'],
                '--password' => $data['password'],
                '--address' => $data['address'] ?? null,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with(['error_message' => 'Setup failed: ' . $e->getMessage()]);
        }

        return redirect()->route('login')->with('success_message', 'Setup completed successfully, please log in.');
    }
}

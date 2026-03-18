<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AgencyUser;
use App\Models\ClientUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (session('user_id')) {
            return session('user_type') === 'agency'
                ? redirect()->route('agency.dashboard')
                : redirect()->route('client.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Try agency login first
        $agency = AgencyUser::where('email', $request->email)->first();
        if ($agency && Hash::check($request->password, $agency->password_hash)) {
            session([
                'user_id' => $agency->id,
                'user_type' => 'agency',
                'user_naam' => $agency->name,
                'user_email' => $agency->email,
                'user_role' => $agency->role,
            ]);
            return redirect()->route('agency.dashboard');
        }

        // Try client login
        $client_user = ClientUser::with('client')->where('email', $request->email)->first();
        if ($client_user && Hash::check($request->password, $client_user->password_hash)) {
            session([
                'user_id' => $client_user->id,
                'user_type' => 'client',
                'user_naam' => $client_user->naam,
                'user_email' => $client_user->email,
                'client_id' => $client_user->client_id,
                'client_naam' => $client_user->client->naam ?? '',
            ]);
            return redirect()->route('client.dashboard');
        }

        return back()->withErrors(['email' => 'Ongeldige inloggegevens'])->withInput();
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('login');
    }
}

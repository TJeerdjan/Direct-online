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
        if ($agency && $this->checkPassword($request->password, $agency->password_hash)) {
            // Rehash if needed
            $this->rehashIfNeeded($agency, 'password_hash', $request->password);

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
        if ($client_user && $this->checkPassword($request->password, $client_user->password_hash)) {
            // Rehash if needed
            $this->rehashIfNeeded($client_user, 'password_hash', $request->password);

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

    /**
     * Check password against hash, supporting bcrypt, MD5, SHA256, and plain text.
     */
    private function checkPassword(string $input, string $stored): bool
    {
        // Try bcrypt first (Laravel default)
        try {
            if (Hash::check($input, $stored)) {
                return true;
            }
        } catch (\RuntimeException $e) {
            // Not a bcrypt hash, try other methods
        }

        // Try MD5
        if (strlen($stored) === 32 && md5($input) === $stored) {
            return true;
        }

        // Try SHA256
        if (strlen($stored) === 64 && hash('sha256', $input) === $stored) {
            return true;
        }

        // Try plain text (last resort)
        if ($input === $stored) {
            return true;
        }

        return false;
    }

    /**
     * Rehash password to bcrypt if it's stored in an older format.
     */
    private function rehashIfNeeded($user, string $column, string $password): void
    {
        if (!str_starts_with($user->$column, '$2y$')) {
            $user->$column = Hash::make($password);
            $user->save();
        }
    }
}

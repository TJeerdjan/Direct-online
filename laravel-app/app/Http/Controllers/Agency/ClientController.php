<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientUser;
use App\Models\ClientSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::withCount(['projects', 'testimonials', 'formSubmissions'])->orderBy('created_at', 'desc')->get();
        return view('agency.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('agency.clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'naam' => 'required|string|max:100',
            'domain' => 'required|string|max:255',
            'contact_naam' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'plan' => 'required|in:early_bird,starter,growth,webshop',
            'user_naam' => 'required|string|max:255',
            'user_email' => 'required|email|unique:users_DO,email',
            'user_password' => 'required|min:6',
        ]);

        $client = Client::create([
            'naam' => $request->naam,
            'slug' => Str::slug($request->naam),
            'domain' => $request->domain,
            'plan' => $request->plan,
            'status' => 'active',
            'contact_naam' => $request->contact_naam,
            'contact_email' => $request->contact_email,
            'api_key' => Str::random(64),
        ]);

        ClientUser::create([
            'client_id' => $client->id,
            'naam' => $request->user_naam,
            'email' => $request->user_email,
            'password' => Hash::make($request->user_password),
            'role' => 'client_admin',
        ]);

        ClientSetting::create([
            'client_id' => $client->id,
            'site_naam' => $request->naam,
        ]);

        return redirect()->route('agency.clients.index')->with('success', 'Klant succesvol aangemaakt');
    }

    public function edit(Client $client)
    {
        $client->load('users', 'settings');
        return view('agency.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'naam' => 'required|string|max:100',
            'domain' => 'required|string|max:255',
            'plan' => 'required|in:early_bird,starter,growth,webshop',
            'status' => 'required|in:active,suspended,cancelled',
            'contact_naam' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
        ]);

        $client->update($request->only(['naam', 'domain', 'plan', 'status', 'contact_naam', 'contact_email']));
        return redirect()->route('agency.clients.index')->with('success', 'Klant bijgewerkt');
    }

    public function impersonate(Client $client)
    {
        $user = $client->users()->first();
        if (!$user) {
            return back()->with('error', 'Geen gebruiker gevonden voor deze klant');
        }

        session([
            'impersonating' => true,
            'original_user_id' => session('user_id'),
            'original_user_type' => session('user_type'),
            'original_user_naam' => session('user_naam'),
            'user_id' => $user->id,
            'user_type' => 'client',
            'user_naam' => $user->naam,
            'user_email' => $user->email,
            'client_id' => $client->id,
            'client_naam' => $client->naam,
            'taal' => $user->taal ?? 'nl',
        ]);

        return redirect()->route('client.dashboard');
    }

    public function stopImpersonate()
    {
        if (session('impersonating')) {
            session([
                'user_id' => session('original_user_id'),
                'user_type' => session('original_user_type'),
                'user_naam' => session('original_user_naam'),
            ]);
            session()->forget(['impersonating', 'original_user_id', 'original_user_type', 'original_user_naam', 'client_id', 'client_naam']);
        }
        return redirect()->route('agency.dashboard');
    }
}

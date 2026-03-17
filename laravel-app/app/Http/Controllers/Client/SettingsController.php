<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = ClientSetting::firstOrCreate(
            ['client_id' => session('client_id')],
            ['site_naam' => session('client_naam')]
        );
        return view('client.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_naam' => 'nullable|string|max:255',
            'primaire_kleur' => 'nullable|string|max:7',
            'secundaire_kleur' => 'nullable|string|max:7',
            'over_tekst' => 'nullable|string',
            'facebook' => 'nullable|url|max:500',
            'instagram' => 'nullable|url|max:500',
            'linkedin' => 'nullable|url|max:500',
            'telefoon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        $settings = ClientSetting::where('client_id', session('client_id'))->first();
        $settings->update($request->only([
            'site_naam', 'primaire_kleur', 'secundaire_kleur', 'over_tekst',
            'facebook', 'instagram', 'linkedin', 'telefoon', 'email'
        ]));

        return back()->with('success', 'Instellingen opgeslagen');
    }
}

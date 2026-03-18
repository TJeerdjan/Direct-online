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
            ['site_name' => session('client_naam')]
        );
        return view('client.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'primary_color' => 'nullable|string|max:7',
            'accent_color' => 'nullable|string|max:7',
            'social_facebook' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
        ]);

        $settings = ClientSetting::where('client_id', session('client_id'))->first();
        $settings->update($request->only([
            'site_name', 'tagline', 'primary_color', 'accent_color',
            'social_facebook', 'social_instagram', 'social_linkedin', 'social_twitter'
        ]));

        return back()->with('success', 'Instellingen opgeslagen');
    }
}

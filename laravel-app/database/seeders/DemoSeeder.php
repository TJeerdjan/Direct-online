<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\AgencyUser;
use App\Models\Client;
use App\Models\ClientUser;
use App\Models\ClientSetting;
use App\Models\Project;
use App\Models\Testimonial;
use App\Models\FormSubmission;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Agency admin
        AgencyUser::create([
            'naam' => 'Admin Direct-Online',
            'email' => 'admin@direct-online.nl',
            'password' => Hash::make('admin123'),
            'role' => 'super_admin',
        ]);

        // Demo client
        $client = Client::create([
            'naam' => 'Demo Fotograaf',
            'slug' => 'demo-fotograaf',
            'domain' => 'demo-fotograaf.nl',
            'plan' => 'starter',
            'status' => 'active',
            'contact_naam' => 'Jan de Vries',
            'contact_email' => 'jan@demo-fotograaf.nl',
            'api_key' => Str::random(64),
        ]);

        ClientUser::create([
            'client_id' => $client->id,
            'naam' => 'Jan de Vries',
            'email' => 'jan@demo-fotograaf.nl',
            'password' => Hash::make('demo123'),
            'role' => 'client_admin',
            'taal' => 'nl',
        ]);

        ClientSetting::create([
            'client_id' => $client->id,
            'site_naam' => 'Demo Fotograaf',
            'primaire_kleur' => '#129387',
            'secundaire_kleur' => '#f59d0e',
            'email' => 'jan@demo-fotograaf.nl',
            'telefoon' => '06-12345678',
        ]);

        // Portfolio items
        $portfolioItems = [
            ['titel' => 'Bruiloft Amsterdam', 'beschrijving' => 'Prachtige bruiloftfotografie in het hart van Amsterdam.', 'categorie' => 'Bruiloften'],
            ['titel' => 'Portret Studio', 'beschrijving' => 'Professionele portretfotografie in onze studio.', 'categorie' => 'Portretten'],
            ['titel' => 'Bedrijfsevenement', 'beschrijving' => 'Fotografie van het jaarlijkse gala.', 'categorie' => 'Evenementen'],
        ];

        foreach ($portfolioItems as $i => $item) {
            Project::create(array_merge($item, [
                'client_id' => $client->id,
                'slug' => Str::slug($item['titel']),
                'is_actief' => true,
                'volgorde' => $i + 1,
            ]));
        }

        // Testimonials
        Testimonial::create([
            'client_id' => $client->id,
            'naam' => 'Lisa Jansen',
            'bedrijf' => 'Jansen Events',
            'tekst' => 'Fantastische fotograaf! De foto\'s van onze bruiloft zijn prachtig geworden.',
            'score' => 5,
            'is_actief' => true,
            'volgorde' => 1,
        ]);

        Testimonial::create([
            'client_id' => $client->id,
            'naam' => 'Mark van Dijk',
            'bedrijf' => 'Van Dijk BV',
            'tekst' => 'Professioneel en creatief. Aanrader voor bedrijfsfotografie.',
            'score' => 4,
            'is_actief' => true,
            'volgorde' => 2,
        ]);

        // Form submissions
        FormSubmission::insert([
            [
                'client_id' => $client->id,
                'naam' => 'Sophie Bakker',
                'email' => 'sophie@example.com',
                'telefoon' => '06-98765432',
                'bericht' => 'Hallo, ik wil graag een offerte voor mijn bruiloft in juni.',
                'status' => 'nieuw',
                'is_gelezen' => false,
                'is_gearchiveerd' => false,
                'aangemaakt_op' => now(),
            ],
            [
                'client_id' => $client->id,
                'naam' => 'Peter de Boer',
                'email' => 'peter@example.com',
                'telefoon' => '06-11223344',
                'bericht' => 'Kunnen jullie ook productfotografie doen?',
                'status' => 'nieuw',
                'is_gelezen' => false,
                'is_gearchiveerd' => false,
                'aangemaakt_op' => now()->subDay(),
            ],
        ]);

        // Second client
        $client2 = Client::create([
            'naam' => 'Bakkerij de Gouden Aar',
            'slug' => 'bakkerij-gouden-aar',
            'domain' => 'gouden-aar.nl',
            'plan' => 'growth',
            'status' => 'active',
            'contact_naam' => 'Pieter Bakker',
            'contact_email' => 'pieter@gouden-aar.nl',
            'api_key' => Str::random(64),
        ]);

        ClientUser::create([
            'client_id' => $client2->id,
            'naam' => 'Pieter Bakker',
            'email' => 'pieter@gouden-aar.nl',
            'password' => Hash::make('demo123'),
            'role' => 'client_admin',
        ]);

        ClientSetting::create([
            'client_id' => $client2->id,
            'site_naam' => 'Bakkerij de Gouden Aar',
        ]);
    }
}

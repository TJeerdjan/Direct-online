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
        // Agency admin - updateOrCreate to handle existing records
        AgencyUser::updateOrCreate(
            ['email' => 'admin@direct-online.nl'],
            [
                'name' => 'Admin Direct-Online',
                'password_hash' => Hash::make('admin123'),
                'role' => 'super_admin',
            ]
        );

        // Demo client
        $client = Client::updateOrCreate(
            ['slug' => 'demo-fotograaf'],
            [
                'naam' => 'Demo Fotograaf',
                'domain' => 'demo-fotograaf.nl',
                'plan' => 'starter',
                'status' => 'active',
                'contact_naam' => 'Jan de Vries',
                'contact_email' => 'jan@demo-fotograaf.nl',
                'api_key' => Str::random(64),
            ]
        );

        ClientUser::updateOrCreate(
            ['email' => 'jan@demo-fotograaf.nl'],
            [
                'client_id' => $client->id,
                'naam' => 'Jan de Vries',
                'password_hash' => Hash::make('demo123'),
                'role' => 'admin',
            ]
        );

        ClientSetting::updateOrCreate(
            ['client_id' => $client->id],
            [
                'site_name' => 'Demo Fotograaf',
                'primary_color' => '#129387',
                'accent_color' => '#f59d0e',
            ]
        );

        // Portfolio items
        $portfolioItems = [
            ['title' => 'Bruiloft Amsterdam', 'description' => 'Prachtige bruiloftfotografie in het hart van Amsterdam.', 'category' => 'Bruiloften'],
            ['title' => 'Portret Studio', 'description' => 'Professionele portretfotografie in onze studio.', 'category' => 'Portretten'],
            ['title' => 'Bedrijfsevenement', 'description' => 'Fotografie van het jaarlijkse gala.', 'category' => 'Evenementen'],
        ];

        foreach ($portfolioItems as $i => $item) {
            Project::updateOrCreate(
                ['client_id' => $client->id, 'slug' => Str::slug($item['title'])],
                array_merge($item, [
                    'slug' => Str::slug($item['title']),
                    'is_visible' => true,
                    'sort_order' => $i + 1,
                ])
            );
        }

        // Testimonials
        Testimonial::updateOrCreate(
            ['client_id' => $client->id, 'client_name' => 'Lisa Jansen'],
            [
                'client_company' => 'Jansen Events',
                'quote' => 'Fantastische fotograaf! De foto\'s van onze bruiloft zijn prachtig geworden.',
                'rating' => 5,
                'is_visible' => true,
                'sort_order' => 1,
            ]
        );

        Testimonial::updateOrCreate(
            ['client_id' => $client->id, 'client_name' => 'Mark van Dijk'],
            [
                'client_company' => 'Van Dijk BV',
                'quote' => 'Professioneel en creatief. Aanrader voor bedrijfsfotografie.',
                'rating' => 4,
                'is_visible' => true,
                'sort_order' => 2,
            ]
        );

        // Form submissions
        if (FormSubmission::where('client_id', $client->id)->count() === 0) {
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
        }

        // Second client
        $client2 = Client::updateOrCreate(
            ['slug' => 'bakkerij-gouden-aar'],
            [
                'naam' => 'Bakkerij de Gouden Aar',
                'domain' => 'gouden-aar.nl',
                'plan' => 'growth',
                'status' => 'active',
                'contact_naam' => 'Pieter Bakker',
                'contact_email' => 'pieter@gouden-aar.nl',
                'api_key' => Str::random(64),
            ]
        );

        ClientUser::updateOrCreate(
            ['email' => 'pieter@gouden-aar.nl'],
            [
                'client_id' => $client2->id,
                'naam' => 'Pieter Bakker',
                'password_hash' => Hash::make('demo123'),
                'role' => 'admin',
            ]
        );

        ClientSetting::updateOrCreate(
            ['client_id' => $client2->id],
            ['site_name' => 'Bakkerij de Gouden Aar']
        );
    }
}

<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Demo organization
        $org = Organization::create([
            'name' => 'Demo Eventorganisation GmbH',
            'slug' => 'demo-eventorganisation',
            'email' => 'demo@greenevents.at',
            'phone' => '+43 1 234 56 78',
            'address' => 'Musterstraße 1, 1010 Wien',
            'country' => 'AT',
            'vat_number' => 'ATU12345678',
            'website' => 'https://www.demo-events.at',
            'subscription_plan' => 'professional',
            'subscription_status' => 'active',
        ]);

        // Owner user
        User::create([
            'organization_id' => $org->id,
            'name' => 'Maria Muster',
            'email' => 'demo@greenevents.at',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'locale' => 'de',
        ]);

        // Editor user
        User::create([
            'organization_id' => $org->id,
            'name' => 'Thomas Editor',
            'email' => 'editor@greenevents.at',
            'password' => Hash::make('password'),
            'role' => 'editor',
            'locale' => 'de',
        ]);

        $this->command->info('✅ Demo organization created!');
        $this->command->info('   Login: demo@greenevents.at / password');
    }
}

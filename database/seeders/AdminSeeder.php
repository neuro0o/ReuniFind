<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define admin image path in storage
        $imagePath = 'images/profiles/admin_default.png'; // relative to storage/app/public/


        DB::table('users')->updateOrInsert(
            ['userEmail' => 'admin@reunifind.com'], // unique key
            [
                'userName'    => 'ReuniFind Administrator',
                'password'    => Hash::make('admin'), // default password
                'userRole'    => 'Admin',
                'contactInfo' => 'N/A',
                'profileImg'  => $imagePath, // store only relative path
                'created_at'  => now(),
                'updated_at'  => now(),
            ]
        );
    }
}

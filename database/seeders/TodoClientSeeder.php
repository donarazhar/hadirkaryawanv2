<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TodoClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('oauth_clients')->updateOrInsert(
            ['id' => 'b2345678-89ab-cdef-0123-456789abcdef'],
            [
                'name' => 'TODO App',
                'secret' => \Illuminate\Support\Facades\Hash::make('todo-secret-key-1234567890'),
                'redirect_uris' => json_encode([
                    'http://localhost:8002/auth/presensi/callback', 
                    'http://127.0.0.1:8002/auth/presensi/callback',
                    'https://todo.donarazhar.site/auth/presensi/callback'
                ]),
                'grant_types' => json_encode(['authorization_code', 'refresh_token']),
                'revoked' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
        
        $this->command->info('OAuth Client untuk TODO berhasil ditambahkan/diperbarui.');
    }
}

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
                'secret' => 'todo-secret-key-1234567890',
                'provider' => 'users',
                'redirect' => 'https://todo.donarazhar.site/auth/presensi/callback,http://localhost:8002/auth/presensi/callback',
                'personal_access_client' => 0,
                'password_client' => 0,
                'revoked' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $this->command->info('OAuth Client untuk TODO berhasil ditambahkan/diperbarui.');
    }
}

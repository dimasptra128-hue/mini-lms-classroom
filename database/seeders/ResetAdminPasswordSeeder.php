<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class ResetAdminPasswordSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'admin@gmail.com')->first();
        if ($user) {
            // Explicitly hash the password to ensure it's stored correctly
            $user->password = \Illuminate\Support\Facades\Hash::make('admin123');
            $user->save();
        }
    }
}

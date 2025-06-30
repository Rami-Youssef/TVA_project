<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create or update the required user accounts with password 11111111
        User::updateOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'super admin',
                'role' => 'super_admin',
                'password' => Hash::make('11111111')
            ]
        );
        
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'admin',
                'role' => 'admin',
                'password' => Hash::make('11111111')
            ]
        );
        
        User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'user',
                'role' => 'user',
                'password' => Hash::make('11111111')
            ]
        );
    }
}

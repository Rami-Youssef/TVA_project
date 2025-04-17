<?php
namespace Database\Seeders;

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
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'super admin',
            'email' => 'superadmin@gmail.com',
            'role' => 'super_admin',
            'password' => Hash::make('secret')
        ]);
        DB::table('users')->insert([
            'id' => 2,
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'role' => 'super_admin',
            'password' => Hash::make('secret')
        ]);
        DB::table('users')->insert([
            'id' => 3,
            'name' => 'user',
            'email' => 'user@gmail.com',
            'role' => 'user',
            'password' => Hash::make('secret')
        ]);
    }
}

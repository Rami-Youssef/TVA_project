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
        // Keep original user accounts
        User::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'super admin',
                'email' => 'superadmin@gmail.com',
                'role' => 'super_admin',
                'password' => Hash::make('secret')
            ]
        );
        
        User::firstOrCreate(
            ['id' => 2],
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'role' => 'super_admin',
                'password' => Hash::make('secret')
            ]
        );
        
        User::firstOrCreate(
            ['id' => 3],
            [
                'name' => 'user',
                'email' => 'user@gmail.com',
                'role' => 'user',
                'password' => Hash::make('secret')
            ]
        );
        
        // Add 17 more users to reach 20 total
        $roles = ['user', 'admin', 'super_admin'];
        $role_weights = [70, 20, 10]; // 70% users, 20% admins, 10% super_admins
        
        $firstNames = ['Mohammed', 'mehdi', 'Ahmed', 'Aisha', 'Omar', 'Laila', 'Youssef', 'Amina', 
                      'Karim', 'Nadia', 'Hassan', 'Samira', 'Ali', 'Leila', 'Ibrahim', 'Sophia', 'Rachid'];
        $lastNames = ['Alami', 'Bennani', 'Chraibi', 'Tazi', 'Fassi', 'El Mansouri', 'Idrissi', 'Berrada', 
                     'Bouzoubaa', 'El Amrani', 'Benjelloun', 'Saidi', 'Bennis', 'Tahiri', 'El Ouazzani', 'Laraki', 'Daoudi'];
        
        for ($i = 4; $i <= 20; $i++) {
            // Select random name
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $fullName = $firstName . ' ' . $lastName;
            
            // Create email from name (lowercase, no spaces)
            $email = strtolower(str_replace(' ', '.', $fullName)) . '@example.com';
            
            // Select role with weighting
            $random = rand(1, 100);
            $role = 'user'; // Default
            $cumulative = 0;
            
            for ($j = 0; $j < count($roles); $j++) {
                $cumulative += $role_weights[$j];
                if ($random <= $cumulative) {
                    $role = $roles[$j];
                    break;
                }
            }
            
            User::firstOrCreate(
                ['id' => $i],
                [
                    'name' => $fullName,
                    'email' => $email,
                    'role' => $role,
                    'password' => Hash::make('password123')
                ]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {      
       $user = User::create([
            'name' => 'abdallahdadikhy',
            'email' => 'abdallahdadikhy@gmail.com',
            'password' => Hash::make('admin123'), // كلمة المرور
            'phone' => '07700000000',
            'userType' => 'admin',
            'preferredLanguage' => 'ar',
            'preferredTheme' => 'dark',
       ]);
       
       $user = User::create([
            'name' => 'mohamad albadawy',
            'email' => 'mohamad albadawy@gmail.com',
            'password' => Hash::make('admin123'), // كلمة المرور
            'phone' => '07700000000',
            'userType' => 'product_manager',
            'preferredLanguage' => 'ar',
            'preferredTheme' => 'dark',
       ]);

    
    }
}

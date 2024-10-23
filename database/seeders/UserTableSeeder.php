<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $system_ip = getHostByName(getHostName());

        $user = User::create([
            'name' => "Admin",
            'email' => "finance@test.com",
            'phone'=>8555555555,
            'password' => Hash::make('admin_555'),
            'status' => 'active',
            'ip_addr'=> $system_ip,
            'profile' => '',
        ]);
    }
}

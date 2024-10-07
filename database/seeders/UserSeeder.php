<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrNew(['name' => 'PMM']);
        Role::firstOrNew(['name' => 'PM']);
        $r = Role::firstOrNew(['name' => 'Super Admin']);
        $admin = User::factory()->firstOrNew([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('Secret@123')
        ]);

        $admin->assignRole($r);
    }
}

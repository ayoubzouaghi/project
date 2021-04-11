<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'first_name' => 'admin',
            'last_name'  => 'admin',
            'email'      => 'admin@gmail.com',
            'password'   => bcrypt('admin'),
        ]);
        $user->assignRole('admin');
    }
}

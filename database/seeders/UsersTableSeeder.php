<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'id' => \Ramsey\Uuid\Uuid::uuid4(),
            'name' => 'psm',
            'email' => 'a@mail.com',
            'password' => app('hash')->make('12345678')
        ]);
    }
}

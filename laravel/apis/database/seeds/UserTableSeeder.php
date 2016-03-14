<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{

    public function run()
    {
        User::create(array(
            'name'     => 'Shubham Sharma',
            'email'    => 'shubhamsharma160792@gmail.com',
            'password' => Hash::make('123'),
        ));
    }

}
<?php

use Illuminate\Database\Seeder;
use App\User;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(UserTableSeeder::class);
        User::create(array(
            'name'     => 'Shubham Sharma',
            'email'    => 'shubhamsharma160792@gmail.com',
            'password' => Hash::make('123'),
        ));
    }
}

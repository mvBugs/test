<?php

use App\Models\User;
use Illuminate\Database\Seeder;

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
            'name' => 'Admin',
            //'last_name' => 'Optimus',
            'email' => 'admin@app.com',
            'password' => 'password',
            'email_verified_at' => new \DateTime(),
//            'confirmed' => true,
//            'safe' => true,
        ]);

        /*User::create([
            'name' => 'Bob',
            //'last_name' => 'Optimus',
            'email' => 'bob@app.com',
            'password' => 'secret',
            'email_verified_at' => new \DateTime(),
            'confirmed' => true,
        ]);

        User::create([
            'name' => 'Manager',
            //'last_name' => 'Optimus',
            'email' => 'manager@app.com',
            'password' => 'secret',
            'email_verified_at' => new \DateTime(),
            'confirmed' => true,
        ]);

        User::create([
            'name' => 'Seo Manager',
            //'last_name' => 'Optimus',
            'email' => 'seo-manager@app.com',
            'password' => 'secret',
            'email_verified_at' => new \DateTime(),
            'confirmed' => true,
        ]);
        User::create([
            'name' => 'Client',
            //'last_name' => 'Optimus',
            'email' => 'client@app.com',
            'password' => 'secret',
            'email_verified_at' => new \DateTime(),
            'confirmed' => true,
        ]);*/
    }
}

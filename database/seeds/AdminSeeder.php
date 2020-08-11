<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create user
        $user = App\Models\User::firstOrCreate([
            'email' => env('ADMIN_EMAIL'),
        ], [
            'name' => env('ADMIN_NAME'),
            'email' => env('ADMIN_EMAIL'),
            'password' => Hash::make( env('ADMIN_PASSWORD') ),
            ]
        );

        // add roles
        //$user->assignRole('admin');

        // token
        $api_token = $user->createToken("aquabox-{$user->email}", [])->accessToken;
        $user->api_token = $api_token;
        $user->save();
    }
}

<?php

use Illuminate\Database\Seeder;

/*
 * |==============================================================|
 * | Please DO NOT modify this information :                      |
 * |--------------------------------------------------------------|
 * | Author          : Susantokun
 * | Email           : admin@susantokun.com
 * | Instagram       : @susantokun
 * | Website         : http://www.susantokun.com
 * | Youtube         : http://youtube.com/susantokun
 * | File Created    : Friday, 28th February 2020 2:50:20 pm
 * | Last Modified   : Friday, 28th February 2020 2:50:20 pm
 * |==============================================================|
 */

class UsersSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert(
            [
                [
                    'name'              => 'Administrator',
                    'email'             => 'admin@mail.com',
                    'email_verified_at' => now(),
                    'password'          => Hash::make('password'),
                    'remember_token'    => Str::random(10)
                ]
            ]
        );
    }
}

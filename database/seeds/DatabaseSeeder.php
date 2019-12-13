<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       
        DB::table('allowed_users')->insert(['user_email'=>'iqbal.rofikurrahman@kly.id']);
        DB::table('allowed_users')->insert(['user_email'=>'adi.cahyono@kly.id']);
        DB::table('allowed_users')->insert(['user_email'=>'teguh.sumarto@kly.id']);
        
    }
}

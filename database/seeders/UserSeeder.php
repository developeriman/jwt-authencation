<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory;
use Hash;
use App\Models\User; 


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker =Factory::create();
        foreach(range(1, 5) as $index){
            User::create([
                'name'=>$faker->name,
                'email'=>$faker->unique()->email,
                'password'=>Hash::make(12345678)
            ]);

        }
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Mahmoud sherif',
            'email' => 'm@m.com',
        ]);


        $this->call([
            CategorySeeder::class,
            SliderSeeder::class,
            CouponSeeder::class,
        ]);
    }
}

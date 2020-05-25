<?php

use Illuminate\Database\Seeder;

class BlogerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Bloger::class, 5)->create();
    }
}

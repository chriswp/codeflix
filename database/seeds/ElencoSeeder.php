<?php

use Illuminate\Database\Seeder;

class ElencoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Elenco::class,10)->create();
    }
}

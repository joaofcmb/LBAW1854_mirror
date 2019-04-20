<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
     {
         Eloquent::unguard();

         $path_database = 'resources/sql/database.sql';
         $path_data = 'resources/sql/data.sql';

         DB::unprepared(file_get_contents($path_database));
         DB::unprepared(file_get_contents($path_data));
         $this->command->info('Database seeded!');
     }
}

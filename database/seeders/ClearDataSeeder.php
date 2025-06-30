<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClearDataSeeder extends Seeder
{
    /**
     * Run the database seeds to clear all data except users.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks to allow truncating tables with relationships
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear CNSS declarations
        DB::table('cnss')->truncate();
        
        // Clear enterprises
        DB::table('entreprises')->truncate();
        
        // Clear TVA declarations if they exist
        if (DB::getSchemaBuilder()->hasTable('tva_declarations')) {
            DB::table('tva_declarations')->truncate();
        }
        
        // Add any other tables that need to be cleared here
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

<?php

namespace Modules\Crm\Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Crm\Database\Seeders\ShopsTableSeeder;

class CrmDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(ShopsTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
      }
}

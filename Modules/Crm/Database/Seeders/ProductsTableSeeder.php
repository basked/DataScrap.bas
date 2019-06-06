<?php

namespace Modules\Crm\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Crm\Entities\Shop;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('Crm_products')->delete();
        $faker = \Faker\Factory::create();
        $limit = 10;
        for ($i = 0; $i < $limit; $i++) {
            $shops = Shop::all()->pluck('id');
            DB::table('Crm_products')->insert([
                'shop_id' => $faker->randomElement($shops),
                'name' => $faker->name,
                'description' => $faker->text,
                'price' => $faker->numberBetween(0, 1000),
                'quantity' => $faker->numberBetween(0, 100)]);
        }
    }
}

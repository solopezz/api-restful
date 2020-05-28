<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

    	DB::statement('SET FOREIGN_KEY_CHECKS=0');
    	User::truncate();
    	Category::truncate();
    	Product::truncate();
    	Transaction::truncate();
    	DB::table('category_product')->truncate();

        //Omite los enventos 
        User::flushEventListeners();
        Category::flushEventListeners();
        Product::flushEventListeners();
        Transaction::flushEventListeners();
        //
        
    	$quantityUsers = 200;
    	$quantityCategories= 30;
    	$quantityProducts = 1000;
    	$quantityTransactions = 1000;

    	factory(User::class, $quantityUsers)->create();

    	factory(Category::class, $quantityCategories)->create();

    	factory(Product::class, $quantityProducts)->create()->each(
    		function($product) {
    			//Coleccion de categorias pueden ser de 1 a 5 con pluck solo trae el id 
    			$categories = Category::all()->random(mt_rand(1, 5))->pluck('id');
    			//attach union del producto con varias o solo una categoria
    			$product->categories()->attach($categories);
    		}
    	);

    	factory(Transaction::class, $quantityTransactions)->create();


        // $this->call(UserSeeder::class);
    }
}

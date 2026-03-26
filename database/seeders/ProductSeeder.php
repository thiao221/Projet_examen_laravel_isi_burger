<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupère les catégories
        $classique   = Category::where('name', 'Burgers Classiques')->first();
        $special     = Category::where('name', 'Burgers Spéciaux')->first();
        $vegetarien  = Category::where('name', 'Burgers Végétariens')->first();
        $boisson     = Category::where('name', 'Boissons')->first();
        $accomp      = Category::where('name', 'Accompagnements')->first();

        $products = [
            // Burgers Classiques
            [
                'category_id' => $classique->id,
                'name'        => 'ISI Classic',
                'price'       => 3500,
                'description' => 'Le burger classique avec steak haché, salade, tomate et sauce maison.',
                'stock'       => 50,
            ],
            [
                'category_id' => $classique->id,
                'name'        => 'Double Cheese',
                'price'       => 4500,
                'description' => 'Double steak avec double fromage fondu.',
                'stock'       => 30,
            ],
            // Burgers Spéciaux
            [
                'category_id' => $special->id,
                'name'        => 'ISI Burger Spécial',
                'price'       => 5500,
                'description' => 'Notre burger signature avec sauce secrète et ingrédients premium.',
                'stock'       => 20,
            ],
            [
                'category_id' => $special->id,
                'name'        => 'Crispy Chicken',
                'price'       => 4800,
                'description' => 'Filet de poulet croustillant avec sauce ranch.',
                'stock'       => 25,
            ],
            // Burgers Végétariens
            [
                'category_id' => $vegetarien->id,
                'name'        => 'Green ISI',
                'price'       => 4000,
                'description' => 'Steak végétal avec légumes frais et sauce avocat.',
                'stock'       => 15,
            ],
            // Boissons
            [
                'category_id' => $boisson->id,
                'name'        => 'Coca-Cola',
                'price'       => 800,
                'description' => 'Coca-Cola 33cl bien frais.',
                'stock'       => 100,
            ],
            [
                'category_id' => $boisson->id,
                'name'        => 'Jus d\'Orange Frais',
                'price'       => 1200,
                'description' => 'Jus d\'orange pressé à la commande.',
                'stock'       => 40,
            ],
            // Accompagnements
            [
                'category_id' => $accomp->id,
                'name'        => 'Frites Maison',
                'price'       => 1500,
                'description' => 'Frites maison croustillantes.',
                'stock'       => 60,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}

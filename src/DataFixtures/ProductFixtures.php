<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Stock;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        /*
        |--------------------------------------------------------------------------
        | CATEGORIES
        |--------------------------------------------------------------------------
        */

        $snacks = new Category();
        $snacks->setCategoryName('Snacks');
        $snacks->setCategoryDescription('Delicious snack foods');

        $desserts = new Category();
        $desserts->setCategoryName('Desserts');
        $desserts->setCategoryDescription('Sweet desserts and delicacies');

        $drinks = new Category();
        $drinks->setCategoryName('Drinks');
        $drinks->setCategoryDescription('Refreshing beverages');

        $manager->persist($snacks);
        $manager->persist($desserts);
        $manager->persist($drinks);

        /*
        |--------------------------------------------------------------------------
        | PRODUCTS
        |--------------------------------------------------------------------------
        */

        $products = [

            [
                'name' => 'Bico',
                'description' => 'Sweet sticky rice delicacy',
                'image' => 'bico.png',
                'price' => 120,
                'category' => $desserts
            ],

            [
                'name' => 'Dynamite',
                'description' => 'Spicy cheese wrapped chili snack',
                'image' => 'dynamite.png',
                'price' => 50,
                'category' => $snacks
            ],

            [
                'name' => 'Fries',
                'description' => 'Crispy french fries',
                'image' => 'fries.png',
                'price' => 75,
                'category' => $snacks
            ],

            [
                'name' => 'Halo Halo',
                'description' => 'Popular Filipino cold dessert',
                'image' => 'halohalo.png',
                'price' => 95,
                'category' => $desserts
            ],

            [
                'name' => 'Kwek Kwek',
                'description' => 'Deep fried orange quail eggs',
                'image' => 'kwekkwek.png',
                'price' => 40,
                'category' => $snacks
            ],

            [
                'name' => 'Leche Flan',
                'description' => 'Creamy caramel custard dessert',
                'image' => 'lecheflan.png',
                'price' => 150,
                'category' => $desserts
            ],

            [
                'name' => 'Milkshake',
                'description' => 'Cold creamy milkshake drink',
                'image' => 'milkshake.png',
                'price' => 110,
                'category' => $drinks
            ],

            [
                'name' => 'Siopao',
                'description' => 'Steamed bun with savory filling',
                'image' => 'siopao.jpg',
                'price' => 60,
                'category' => $snacks
            ],

            [
                'name' => 'Spiral Potato',
                'description' => 'Deep fried spiral potato snack',
                'image' => 'spiralpotato.png',
                'price' => 85,
                'category' => $snacks
            ],

            [
                'name' => 'Tocino',
                'description' => 'Sweet cured pork meal',
                'image' => 'tocino.png',
                'price' => 180,
                'category' => $snacks
            ],

            [
                'name' => 'Ube Cake',
                'description' => 'Soft ube flavored cake',
                'image' => 'ubecake.png',
                'price' => 320,
                'category' => $desserts
            ],
        ];

        foreach ($products as $data) {

            /*
            |--------------------------------------------------------------------------
            | STOCK
            |--------------------------------------------------------------------------
            */

            $stock = new Stock();
            $stock->setQuantity(rand(10, 50));
            $stock->setStockDescription($data['name'] . ' stock');
            $stock->setMinQuantity(5);
            $stock->setMaxQuantity(100);
            $stock->setUnit('pcs');

            $manager->persist($stock);

            /*
            |--------------------------------------------------------------------------
            | PRODUCT
            |--------------------------------------------------------------------------
            */

            $product = new Product();

            $product->setProductName($data['name']);
            $product->setProductDescription($data['description']);
            $product->setImage($data['image']);
            $product->setPrice($data['price']);

            $product->setCategory($data['category']);
            $product->setStock($stock);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
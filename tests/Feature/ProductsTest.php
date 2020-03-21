<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Product;
use App\User;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create([
            'email' => 'admin@admin.com',
            'password' => bcrypt('password123'),
        ]);
    }

    public function test_homepage_contains_empty_products_table()
    {
        $response = $this->actingAs($this->user)->get('/');

        $response->assertStatus(200);

        $response->assertSee('No products found');
    }

    public function test_homepage_contains_non_empty_products_table()
    {
        // Create a product
        $product = Product::create([
            'name' => 'Test Product',
            'price' => 99.99
        ]);

        $response = $this->actingAs($this->user)->get('/');

        $response->assertStatus(200);

        $response->assertDontSee('No products found');

        $view_products = $response->viewData('products');

        $this->assertEquals($product->name, $view_products->first()->name);
    }

    public function test_paginated_products_table_doesnt_show_11th_record()
    {
        $products = factory(Product::class, 11)->create(['price' => 9.99]);
        // for ($i = 1; $i <= 11; $i++) {
        //     $product = Product::create([
        //         'name' => 'Product ' . $i,
        //         'price' => rand(10, 99)
        //     ]);
        // }

        $response = $this->actingAs($this->user)->get('/');

        $response->assertDontSee($products->last()->name);
    }
}

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

    private function create_user($is_admin = 0)
    {
        $this->user = factory(User::class)->create([
            'email' => ($is_admin) ? 'admin@admin.com' : 'user@user.com',
            'password' => bcrypt('password123'),
            'is_admin' => $is_admin,
        ]);
    }

    public function test_homepage_contains_empty_products_table()
    {
        $this->create_user();
        $response = $this->actingAs($this->user)->get('/products');

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

        $this->create_user();
        $response = $this->actingAs($this->user)->get('/products');

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

        $this->create_user();
        $response = $this->actingAs($this->user)->get('/products');

        $response->assertDontSee($products->last()->name);
    }

    public function test_admin_can_see_product_create_button()
    {
        $this->create_user(1);
        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);
        $response->assertSee('Add new product');
    }

    public function test_non_admin_user_cannot_see_product_create_button()
    {
        $this->create_user();
        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);
        $response->assertDontSee('Add new product');
    }

    public function test_admin_can_access_product_create_page()
    {
        $this->create_user(1);
        $response = $this->actingAs($this->user)->get('products/create');

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_product_create_page()
    {
        $this->create_user();
        $response = $this->actingAs($this->user)->get('products/create');

        $response->assertStatus(403);
    }
}

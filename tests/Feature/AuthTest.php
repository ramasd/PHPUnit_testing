<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class AuthTest extends TestCase
{

    use RefreshDatabase;

    public function test_login_redirect_successfully()
    {
        // Create a use
        factory(User::class)->create([
            'email' => 'admin@admin.com',
            'password' => bcrypt('password123'),
        ]);

        // Post to "/login"
        $response = $this->post('/login', ['email' => 'admin@admin.com', 'password' => 'password123']);

        // Assert redirect 302 to "/home"
        $response->assertstatus(302);
        $response->assertRedirect('/home');

    }

    public function test_authenticated_user_can_access_products_table()
    {
        // Create a use
        $user = factory(User::class)->create([
            'email' => 'admin@admin.com',
            'password' => bcrypt('password123'),
        ]);

        // Go to homepage "/"
        $response = $this->actingAs($user)->get('/');

        // Assert status 200
        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_cannot_access_products_table()
    {
        // Go to homepage "/"
        $response = $this->get('/');

        // Assert status not 200
        $response->assertStatus(302);
        
        // Assert redirect to "/login"
        $response->assertRedirect('/login');
    }
}

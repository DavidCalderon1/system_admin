<?php

namespace Tests\Feature\app\Http\Controller\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class UserCreateControllerTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testStoreUser()
    {
        $user = User::where('email', 'super@admin.com')->first();

       $response = $this->actingAs($user)->post(route('user-create'), [
            "name" => "Juan Camilo",
            "email" => "juanca1158@hotmail.com",
            "password" => "123456789",
            "password_confirmation" => "123456789",
        ], [
            'Accept' => 'application/json'
        ]);

       dd($response->getContent());
    }
}

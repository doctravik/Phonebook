<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateUserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_create_new_user()
    {
        $response = $this->json('post', '/api/users', [
            'name' => 'JohnDoe'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['name' => 'JohnDoe']);
    }

    /** @test */
    public function it_cannot_create_user_without_name()
    {
        $response = $this->json('post', '/api/users', [
            'name' => ''
        ]);

        $response->assertStatus(422);
        $this->assertCount(0, User::all());
    }
}

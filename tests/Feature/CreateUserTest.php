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
        $this->assertArrayHasKey('name', $response->json());
        $this->assertCount(0, User::all());
    }

    /** @test */
    public function it_cannot_create_user_with_not_unique_name()
    {
        $john = factory(User::class)->create(['name' => 'john']);

        $response = $this->json('post', "/api/users", [
            'name' => 'john'
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('name', $response->json());
        $this->assertCount(1, User::all());
    }

    /** @test */
    public function it_can_add_phone_number_to_the_user()
    {
        $response = $this->json('post', '/api/users', [
            'name' => 'JohnDoe',
            'phone_number' => '1111111111'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['name' => 'JohnDoe']);
        $this->assertEquals('1111111111', User::first()->phones()->first()->number);
    }
}

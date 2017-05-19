<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateUserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_update_user_name()
    {
        $user = factory(User::class)->create(['name' => 'john']);

        $response = $this->json('put', "/api/users/{$user->id}", [
            'name' => 'leon'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('leon', $user->fresh()->name);
    }

    /** @test */
    public function it_cannot_update_user_without_name()
    {
        $user = factory(User::class)->create(['name' => 'john']);

        $response = $this->json('put', "/api/users/{$user->id}", [
            'name' => ''
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('name', $response->json());
        $this->assertEquals('john', $user->fresh()->name);
    }

    /** @test */
    public function it_cannot_update_user_with_the_same_as_another_user()
    {
        $john = factory(User::class)->create(['name' => 'john']);
        $leon = factory(User::class)->create(['name' => 'leon']);

        $response = $this->json('put', "/api/users/{$john->id}", [
            'name' => 'leon'
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('name', $response->json());
        $this->assertEquals('john', $john->fresh()->name);
    }

    /** @test */
    public function it_can_update_user_if_name_remains_the_same()
    {
        $john = factory(User::class)->create(['name' => 'john']);

        $response = $this->json('put', "/api/users/{$john->id}", [
            'name' => 'john'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('john', $john->fresh()->name);
    }
}

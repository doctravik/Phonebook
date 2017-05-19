<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteUserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_delete_user()
    {
        $user = factory(User::class)->create();

        $response = $this->json('delete', "/api/users/{$user->id}");

        $response->assertStatus(200);
        $this->assertCount(0, User::all());
    }
}

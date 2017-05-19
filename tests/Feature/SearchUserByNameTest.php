<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SearchUserByNameTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_search_users_by_name()
    {
        $userOne = factory(User::class)->create(['name' => 'JohnDoe']);

        $response = $this->json('get', '/api/users/search/Doe');

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $userOne->id]);
    }
}

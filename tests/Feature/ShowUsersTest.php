<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowUsersTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_show_all_users()
    {
        $users = factory(User::class, 2)->create();
 
        $response = $this->json('get', '/api/users');

        $response->assertStatus(200);
        $response->assertJson($users->toArray());
    }
}

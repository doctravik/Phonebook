<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function user_can_be_found_by_name_with_like_scope()
    {
        factory(User::class)->create(['name' => 'JohnDoe']);

        $user = User::findLikeName('Johndoe')->first();

        $this->assertNotNull($user);
    }

    /** @test */
    public function user_can_be_found_when_name_is_equals_partly()
    {
        factory(User::class)->create(['name' => 'JohnDoe']);

        $user = User::findLikeName('John')->first();

        $this->assertNotNull($user);
    }

    /** @test */
    public function user_cannot_be_found_when_name_isnt_exist()
    {
        factory(User::class)->create(['name' => 'JohnDoe']);

        $users = User::findLikeName('Leon');

        $this->assertNull($users->first());
       $this->assertEmpty($users->get());
    }

    /** @test */
    public function searching_users_by_name_is_case_insensitive()
    {
        factory(User::class)->create(['name' => 'JohnDoe']);

        $users = User::findLikeName('john')->first();

        $this->assertNotNull($users);
    }
}

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
    public function user_can_add_contact()
    {
        [$john, $leon] = factory(User::class, 2)->create();


        $contact = $john->addContact(['name' => 'John Doe']);

        $this->assertTrue($john->hasContact($contact));
        $this->assertFalse($leon->hasContact($contact));
    }
}

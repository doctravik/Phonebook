<?php

namespace Tests\Feature;

use App\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowContactsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_show_all_contacts()
    {
        $contacts = factory(Contact::class, 2)->create();
 
        $response = $this->json('get', '/api/contacts');

        $response->assertStatus(200);
        $response->assertJson($contacts->toArray());
    }
}

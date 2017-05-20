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
        $contact = factory(Contact::class)->create();
 
        $response = $this->json('get', '/api/contacts');

        $response->assertStatus(200);

        $response->assertJsonFragment(['id' => $contact->id]);
    }
}

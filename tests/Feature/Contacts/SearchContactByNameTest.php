<?php

namespace Tests\Feature;

use App\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SearchContactByNameTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_search_contacts_by_name()
    {
        $contactOne = factory(Contact::class)->create(['name' => 'JohnDoe']);

        $response = $this->json('get', '/api/contacts/search/Doe');

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $contactOne->id]);
    }
}

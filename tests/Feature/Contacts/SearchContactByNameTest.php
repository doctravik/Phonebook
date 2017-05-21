<?php

namespace Tests\Feature;

use App\User;
use App\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SearchContactByNameTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function unauthenticated_user_cannot_search_contacts()
    {
        $contactOne = factory(Contact::class)->create(['name' => 'JohnDoe']);

        $response = $this->json('get', '/api/contacts/search/Doe');

        $response->assertStatus(401);
    }

    /** @test */
    public function authenticated_user_can_search_contacts_by_name()
    {
        $contact = factory(Contact::class)->create(['name' => 'JohnDoe']);

        $response = $this->actingAs($contact->user)->json('get', '/api/contacts/search/Doe');

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $contact->id]);
    }

    /** @test */
    public function authenticated_user_cannot_find_contacts_of_another_user()
    {
        $contactOne = factory(Contact::class)->create(['name' => 'JohnDoe']);
        $contactTwo = factory(Contact::class)->create(['name' => 'JohnDoe']);

        $response = $this->actingAs($contactOne->user)->json('get', '/api/contacts/search/Doe');

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $contactOne->id]);
        $response->assertJsonMissing(['id' => $contactTwo->id]);
    }
}

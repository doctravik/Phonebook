<?php

namespace Tests\Feature;

use App\User;
use App\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowContactsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function unauthenticated_user_cannot_view_all_own_contacts()
    {
        $contact = factory(Contact::class)->create();
 
        $response = $this->json('get', '/api/contacts');

        $response->assertStatus(401);
        $response->assertJsonMissing(['id' => $contact->id]);
    }

    /** @test */
    public function authenticated_user_can_view_own_contact()
    {
        $contact = factory(Contact::class)->create();
 
        $response = $this->actingAs($contact->user)->json('get', '/api/contacts');

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $contact->id]);
    }

    /** @test */
    public function user_cannot_view_contact_of_another_user()
    {
        $user = factory(User::class)->create();
        $contact = factory(Contact::class)->create();
 
        $response = $this->actingAs($user)->json('get', '/api/contacts');

        $response->assertStatus(200);
        $response->assertJsonMissing(['id' => $contact->id]);
    }
}

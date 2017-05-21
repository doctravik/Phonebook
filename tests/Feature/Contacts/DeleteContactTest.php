<?php

namespace Tests\Feature;

use App\User;
use App\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteContactTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function unauthenticated_user_cannot_delete_contact()
    {
        $contact = factory(Contact::class)->create();

        $response = $this->json('delete', "/api/contacts/{$contact->id}");

        $response->assertStatus(401);
        $this->assertCount(1, Contact::all());
    }

    /** @test */
    public function authenticated_user_cannot_delete_contact_of_another_user()
    {
        $user = factory(User::class)->create();
        $contact = factory(Contact::class)->create();

        $response = $this->actingAs($user)->json('delete', "/api/contacts/{$contact->id}");

        $response->assertStatus(403);
        $this->assertCount(1, Contact::all());
    }

    /** @test */
    public function authenticated_user_can_delete_own_contact()
    {
        $user = factory(User::class)->create();
        $contact = factory(Contact::class)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->json('delete', "/api/contacts/{$contact->id}");

        $response->assertStatus(200);
        $this->assertCount(0, Contact::all());
    }
}

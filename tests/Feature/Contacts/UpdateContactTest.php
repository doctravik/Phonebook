<?php

namespace Tests\Feature;

use App\User;
use App\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateContactTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function unauthenticated_cannot_update_contact_name()
    {
        $contact = factory(Contact::class)->create();

        $response = $this->json('put', "/api/contacts/{$contact->id}");

        $response->assertStatus(401);
    }

    /** @test */
    public function user_can_update_name_of_own_contact()
    {
        $contact = factory(Contact::class)->create(['name' => 'john']);

        $response = $this->actingAs($contact->user)->json('put', "/api/contacts/{$contact->id}", [
            'name' => 'leon'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('leon', $contact->fresh()->name);
    }

    /** @test */
    public function user_cannot_update_name_of_contact_of_another_user()
    {
        $anotherUser = factory(User::class)->create();
        $contact = factory(Contact::class)->create(['name' => 'john']);

        $response = $this->actingAs($anotherUser)->json('put', "/api/contacts/{$contact->id}", [
            'name' => 'leon'
        ]);

        $response->assertStatus(403);
        $this->assertEquals('john', $contact->fresh()->name);
    }

    /** @test */
    public function user_cannot_update_contact_with_empty_name()
    {
        $contact = factory(Contact::class)->create(['name' => 'john']);

        $response = $this->actingAs($contact->user)->json('put', "/api/contacts/{$contact->id}", [
            'name' => ''
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('name', $response->json());
        $this->assertEquals('john', $contact->fresh()->name);
    }

    /** @test */
    public function user_cannot_update_contact_with_the_same_as_another_own_contact()
    {
        $user = factory(User::class)->create();
        $john = factory(Contact::class)->create(['name' => 'john', 'user_id' => $user->id]);
        $leon = factory(Contact::class)->create(['name' => 'leon', 'user_id' => $user->id]);

        $response = $this->actingAs($user)->json('put', "/api/contacts/{$john->id}", [
            'name' => 'leon'
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('name', $response->json());
        $this->assertEquals('john', $john->fresh()->name);
    }

    /** @test */
    public function it_can_update_contact_with_the_same_as_contact_of_another_user()
    {
        $user = factory(User::class)->create();
        $john = factory(Contact::class)->create(['name' => 'john', 'user_id' => $user->id]);
        $leon = factory(Contact::class)->create(['name' => 'leon']);

        $response = $this->actingAs($user)->json('put', "/api/contacts/{$john->id}", [
            'name' => 'leon'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('leon', $john->fresh()->name);
    }

    /** @test */
    public function user_can_update_contact_if_name_remains_the_same()
    {
        $contact = factory(Contact::class)->create(['name' => 'john']);

        $response = $this->actingAs($contact->user)->json('put', "/api/contacts/{$contact->id}", [
            'name' => 'john'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('john', $contact->fresh()->name);
    }
}

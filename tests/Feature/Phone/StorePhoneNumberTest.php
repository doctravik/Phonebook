<?php

namespace Tests\Feature\Phone;

use App\User;
use App\Phone;
use App\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StorePhoneNumberTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function unauthenticated_user_cannot_store_phone_number()
    {
        $contact = factory(Contact::class)->create();

        $response = $this->json('post', "/api/contacts/{$contact->id}/phones");

        $response->assertStatus(401);
        $this->assertCount(0, Phone::all());
    }

    /** @test */
    public function authenticated_user_can_store_phone_number_of_the_contact()
    {
        $contact = factory(Contact::class)->create();

        $response = $this->actingAs($contact->user)->json('post', "/api/contacts/{$contact->id}/phones", [
            'phone_number' => '0123456789'
        ]);

        $response->assertStatus(200);
        $this->assertCount(1, $contact->phones);
        $this->assertEquals('0123456789', Phone::first()->phone_number);
    }

    /** @test */
    public function user_cannot_store_phone_number_of_the_contact_of_another_user()
    {
        $anotherUser = factory(User::class)->create();
        $contact = factory(Contact::class)->create();

        $response = $this->actingAs($anotherUser)->json('post', "/api/contacts/{$contact->id}/phones", [
            'phone_number' => '0123456789'
        ]);

        $response->assertStatus(403);
        $this->assertCount(0, Phone::all());
    }

    /** @test */
    public function user_cannot_store_phone_number_with_empty_value()
    {
        $contact = factory(Contact::class)->create();

        $response = $this->actingAs($contact->user)->json('post', "/api/contacts/{$contact->id}/phones", [
            'phone_number' => ''
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('phone_number', $response->json());
        $this->assertCount(0, Phone::all());
    }

    /** @test */
    public function user_cannot_store_phone_number_if_another_own_contact_has_the_same_number()
    {
        $contact = factory(Contact::class)->create();
        $phone = factory(Phone::class)->create([
            'phone_number' => '0123456789', 
            'contact_id' => $contact->id,
            'user_id' => $contact->user_id
        ]);

        $response = $this->actingAs($contact->user)
            ->json('post', "/api/contacts/{$contact->id}/phones", [
                'phone_number' => '0123456789'
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('phone_number', $response->json());
        $this->assertCount(1, Phone::all());
    }

    /** @test */
    public function user_can_store_phone_number_if_another_user_has_the_same_number_in_contacts()
    {
        $contact = factory(Contact::class)->create();
        $phone = factory(Phone::class)->create(['phone_number' => '0123456789']);

        $response = $this->actingAs($contact->user)
            ->json('post', "/api/contacts/{$contact->id}/phones", [
                'phone_number' => '0123456789'
        ]);

        $response->assertStatus(200);
        $this->assertCount(2, Phone::all());
    }

    /** @test */
    public function it_cannot_store_phone_number_if_number_has_length_more_than_20()
    {
        $contact = factory(Contact::class)->create();

        $response = $this->actingAs($contact->user)
            ->json('post', "/api/contacts/{$contact->id}/phones", [
                'phone_number' => '0123456789012345678901'
            ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('phone_number', $response->json());
    }
}

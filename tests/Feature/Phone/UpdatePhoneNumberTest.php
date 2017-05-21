<?php

namespace Tests\Feature;

use App\User;
use App\Phone;
use App\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdatePhoneNumberTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function unauthenticated_user_cannot_update_phone_number()
    {
        $phone = factory(Phone::class)->create();

        $response = $this->json('patch', "/api/phones/{$phone->id}", [
            'phone_number' => '2222222222'
        ]);

        $response->assertStatus(401);
        $this->assertNotEquals('2222222222', Phone::first()->phone_number);
    }

    /** @test */
    public function authenticated_user_can_update_phone_number()
    {
        $phone = factory(Phone::class)->create(['phone_number' => '0123456789']);
        $contact = $phone->contact;

        $response = $this->actingAs($contact->user)
            ->json('patch', "/api/phones/{$phone->id}", [
                'phone_number' => '9876543210'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('9876543210', Phone::first()->phone_number);
    }

    /** @test */
    public function user_cannot_update_phone_number_of_another_user()
    {
        $anotherUser = factory(User::class)->create();
        $phone = factory(Phone::class)->create();

        $response = $this->actingAs($anotherUser)->json('patch', "/api/phones/{$phone->id}", [
            'phone_number' => '0123456789'
        ]);

        $response->assertStatus(403);
        $this->assertNotEquals('0123456789', Phone::first()->phone_number);
    }

    /** @test */
    public function user_cannot_update_phone_number_with_empty_value()
    {
        $phone = factory(Phone::class)->create();
        $contact = $phone->contact;

        $response = $this->actingAs($contact->user)
            ->json('patch', "/api/phones/{$phone->id}", [
                'phone_number' => ''
            ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('phone_number', $response->json());
    }

    /** @test */
    public function user_cannot_update_phone_number_if_number_has_length_more_than_20()
    {
        $phone = factory(Phone::class)->create();
        $contact = $phone->contact;

        $response = $this->actingAs($contact->user)
            ->json('patch', "/api/phones/{$phone->id}", [
                'phone_number' => '0123456789012345678901'
            ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('phone_number', $response->json());
        $this->assertNotEquals('0123456789012345678901', Phone::first()->phone_number);
    }

    /** @test */
    public function user_can_update_phone_number_if_another_user_has_the_same_number()
    {
        $anotherPhone = factory(Phone::class)->create(['phone_number' => '9876543210']);
        $phone = factory(Phone::class)->create(['phone_number' => '0123456789']);

        $response = $this->actingAs($phone->user)
            ->json('patch', "/api/phones/{$phone->id}", [
                'phone_number' => '9876543210'
            ]);

        $response->assertStatus(200);
        $this->assertEquals('9876543210', $phone->fresh()->phone_number);
    }

    /** @test */
    public function user_cannot_update_phone_number_if_has_the_same_own_number()
    {
        $contact = factory(Contact::class)->create();
        $phoneOne = factory(Phone::class)->create([
            'phone_number' => '0123456789',
            'contact_id' => $contact->id,
            'user_id' => $contact->user_id 
        ]);
        $phoneTwo = factory(Phone::class)->create([
            'phone_number' => '9876543210',
            'contact_id' => $contact->id,
            'user_id' => $contact->user_id
        ]);

        $response = $this->actingAs($phoneOne->user)
            ->json('patch', "/api/phones/{$phoneOne->id}", [
                'phone_number' => '9876543210'
            ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('phone_number', $response->json());
        $this->assertEquals('0123456789', $phoneOne->fresh()->phone_number);
    }
}

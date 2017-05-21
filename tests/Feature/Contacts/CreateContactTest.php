<?php

namespace Tests\Feature;

use App\User;
use App\Phone;
use App\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateContactTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function unauthenticated_user_cannot_create_new_contact()
    {
        $response = $this->json('post', '/api/contacts');

        $response->assertStatus(401);
        $this->assertCount(0, Contact::all());        
    }

    /** @test */
    public function authenticated_user_can_create_new_contact()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('post', '/api/contacts', [
            'name' => 'JohnDoe',
            'phone_number' => '0123456789'
        ]);

        $response->assertStatus(200);
        $this->assertCount(1, $user->contacts);
    }

    /** @test */
    public function user_cannot_create_contact_with_empty_name()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('post', '/api/contacts', [
            'name' => ''
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('name', $response->json());
        $this->assertCount(0, Contact::all());
    }

    /** @test */
    public function user_cannot_create_contact_without_name_size_less_than_3()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('post', '/api/contacts', [
            'name' => '12'
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('name', $response->json());
        $this->assertCount(0, Contact::all());
    }

    /** @test */
    public function user_can_create_contact_with_the_same_name_as_a_contact_of_another_user()
    {
        $user = factory(User::class)->create();
        $contact = factory(Contact::class)->create(['name' => 'john']);

        $response = $this->actingAs($user)->json('post', "/api/contacts", [
            'name' => 'john',
            'phone_number' => '0123456789'
        ]);

        $response->assertStatus(200);
        $this->assertCount(2, Contact::all());
    }

    /** @test */
    public function user_cannot_create_contact_with_the_same_name_another_own_contact()
    {
        $contact = factory(Contact::class)->create(['name' => 'john']);

        $response = $this->actingAs($contact->user)->json('post', "/api/contacts", [
            'name' => 'john',
            'phone_number' => '0123456789'
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('name', $response->json());
        $this->assertCount(1, Contact::all());
    }

    /** @test */
    public function user_cannot_create_contact_without_phone_number()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('post', '/api/contacts', [
            'name' => 'JohnDoe'
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('phone_number', $response->json());
        $this->assertCount(0, Contact::all());
    }

    /** @test */
    public function user_cannot_create_contact_with_empty_phone_number()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('post', '/api/contacts', [
            'name' => 'JohnDoe',
            'phone_number' => ''
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('phone_number', $response->json());
        $this->assertCount(0, Contact::all());
    }

    /** @test */
    public function user_can_add_phone_number_to_the_contact()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('post', '/api/contacts', [
            'name' => 'JohnDoe',
            'phone_number' => '0123456789'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('contacts', ['name' => 'JohnDoe']);
        $this->assertEquals('0123456789', Phone::first()->phone_number);
    }

    /** @test */
    public function user_can_create_contact_with_the_same_phone_number_as_a_contact_of_another_user()
    {
        $anotherUser = factory(User::class)->create();
        $phone = factory(Phone::class)->create(['phone_number' => '0123456789']);

        $response = $this->actingAs($anotherUser)->json('post', '/api/contacts', [
            'name' => 'john',
            'phone_number' => '0123456789'
        ]);

        $response->assertStatus(200);
        $this->assertCount(2, Contact::all());
    }

    /** @test */
    public function user_cannot_create_contact_with_the_same_phone_number_as_another_own_contact()
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

        $response = $this->actingAs($phoneOne->user)->json('post', '/api/contacts', [
                'phone_number' => '9876543210'
            ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('phone_number', $response->json());
        $this->assertEquals('0123456789', $phoneOne->fresh()->phone_number);
    }
}

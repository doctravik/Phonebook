<?php

namespace Tests\Feature;

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
    public function it_can_create_new_contact()
    {
        $response = $this->json('post', '/api/contacts', [
            'name' => 'JohnDoe',
            'phone_number' => '0123456789'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('contacts', ['name' => 'JohnDoe']);
    }

    /** @test */
    public function it_cannot_create_contact_without_name()
    {
        $response = $this->json('post', '/api/contacts', [
            'name' => ''
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('name', $response->json());
        $this->assertCount(0, Contact::all());
    }

    /** @test */
    public function it_cannot_create_contact_with_not_unique_name()
    {
        $john = factory(Contact::class)->create(['name' => 'john']);

        $response = $this->json('post', "/api/contacts", [
            'name' => 'john'
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('name', $response->json());
        $this->assertCount(1, Contact::all());
    }

    /** @test */
    public function it_cannot_create_contact_with_empty_phone_number()
    {
        $response = $this->json('post', '/api/contacts', [
            'name' => 'JohnDoe',
            'phone_number' => ''
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('phone_number', $response->json());
        $this->assertCount(0, Contact::all());
    }

    /** @test */
    public function it_cannot_create_contact_with_not_unique_phone_number()
    {
        factory(Phone::class)->create(['phone_number' => '123456']);

        $response = $this->json('post', '/api/contacts', [
            'name' => 'JohnDoe',
            'phone_number' => '123456'
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('phone_number', $response->json());
        $this->assertDatabaseMissing('contacts', ['name' => 'JohnDoe']);
    }

    /** @test */
    public function it_can_add_phone_number_to_the_contact()
    {
        $response = $this->json('post', '/api/contacts', [
            'name' => 'JohnDoe',
            'phone_number' => '1111111111'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('contacts', ['name' => 'JohnDoe']);
        $this->assertEquals('1111111111', Contact::first()->phones()->first()->phone_number);
    }
}

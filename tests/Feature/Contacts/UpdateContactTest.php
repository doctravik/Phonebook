<?php

namespace Tests\Feature;

use App\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateContactTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_update_contact_name()
    {
        $contact = factory(Contact::class)->create(['name' => 'john']);

        $response = $this->json('put', "/api/contacts/{$contact->id}", [
            'name' => 'leon'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('leon', $contact->fresh()->name);
    }

    /** @test */
    public function it_cannot_update_contact_without_name()
    {
        $contact = factory(Contact::class)->create(['name' => 'john']);

        $response = $this->json('put', "/api/contacts/{$contact->id}", [
            'name' => ''
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('name', $response->json());
        $this->assertEquals('john', $contact->fresh()->name);
    }

    /** @test */
    public function it_cannot_update_contact_with_the_same_as_another_contact()
    {
        $john = factory(Contact::class)->create(['name' => 'john']);
        $leon = factory(Contact::class)->create(['name' => 'leon']);

        $response = $this->json('put', "/api/contacts/{$john->id}", [
            'name' => 'leon'
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('name', $response->json());
        $this->assertEquals('john', $john->fresh()->name);
    }

    /** @test */
    public function it_can_update_contact_if_name_remains_the_same()
    {
        $john = factory(Contact::class)->create(['name' => 'john']);

        $response = $this->json('put', "/api/contacts/{$john->id}", [
            'name' => 'john'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('john', $john->fresh()->name);
    }
}

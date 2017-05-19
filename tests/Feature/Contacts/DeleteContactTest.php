<?php

namespace Tests\Feature;

use App\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteContactTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_delete_contact()
    {
        $contact = factory(Contact::class)->create();

        $response = $this->json('delete', "/api/contacts/{$contact->id}");

        $response->assertStatus(200);
        $this->assertCount(0, Contact::all());
    }
}

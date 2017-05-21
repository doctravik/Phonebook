<?php

namespace Tests\Feature\Phone;

use App\Contact;
use App\Phone;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StorePhoneNumberTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function contact_can_store_phone_number()
    {
        $user = factory(Contact::class)->create();

        $response = $this->json('post', "/api/contacts/{$user->id}/phones", [
            'phone_number' => '0123456789'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('0123456789', $user->fresh()->phones()->first()->phone_number);
    }

    /** @test */
    public function contact_cannot_store_phone_number_with_empty_value()
    {
        $user = factory(Contact::class)->create();

        $response = $this->json('post', "/api/contacts/{$user->id}/phones", [
            'phone_number' => ''
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('phone_number', $response->json());
        $this->assertCount(0, Phone::all());
    }

    /** @test */
    public function contact_cannot_store_phone_number_with_not_unique_value()
    {
        $user = factory(Contact::class)->create(['name' => 'john']);
        $user->addPhone('0123456789');

        $response = $this->json('post', "/api/contacts/{$user->id}/phones", [
            'phone_number' => '0123456789'
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('phone_number', $response->json());
    }
}

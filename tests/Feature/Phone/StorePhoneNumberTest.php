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
        $john = factory(Contact::class)->create(['name' => 'john']);

        $response = $this->json('post', "/api/contacts/{$john->id}/phones", [
            'phone_number' => '2222222222'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('2222222222', $john->fresh()->phones()->first()->phone_number);
    }

    /** @test */
    public function contact_cannot_store_phone_number_with_empty_value()
    {
        $john = factory(Contact::class)->create(['name' => 'john']);

        $response = $this->json('post', "/api/contacts/{$john->id}/phones", [
            'phone_number' => ''
        ]);

        $response->assertStatus(422);
        $this->assertCount(0, Phone::all());
    }
}
